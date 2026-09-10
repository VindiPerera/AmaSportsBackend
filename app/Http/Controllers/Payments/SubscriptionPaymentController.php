<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * Plain server-rendered pages PayPal redirects the payer's browser to after
 * hosted checkout — hit directly by the browser, not by the mobile app, so
 * these stay outside the Sanctum-protected /api surface (routes/web.php).
 *
 * The mobile app never trusts this redirect happening at all: it opens the
 * PayPal approval URL in an in-app browser (WebBrowser.openAuthSessionAsync)
 * and, once the browser is dismissed for any reason, polls
 * GET /api/player/subscription-status itself. That's what actually unlocks
 * the UI — these pages exist to (a) actually capture the payment, (b) give
 * the payer something to look at before the browser closes, and (c) bounce
 * the browser to the app's deep link scheme afterward (see
 * config('subscription.mobile_return_scheme') / result.blade.php) so
 * openAuthSessionAsync detects the redirect and auto-closes the sheet.
 */
class SubscriptionPaymentController extends Controller
{
    /** GET /payments/subscriptions/return?token={paypal_order_id}&PayerID=... */
    public function return(Request $request, PayPalService $paypal): View
    {
        $orderId = (string) $request->query('token');
        $subscription = Subscription::where('paypal_order_id', $orderId)->first();

        if (! $subscription) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Payment not found',
                'message' => "We couldn't find that order. If you were charged, please contact support.",
            ]);
        }

        // Idempotent: a webhook or an earlier hit on this same return URL
        // may have already activated it (PayPal can retry/re-open this
        // page). Never re-capture or overwrite an already-settled row.
        if ($subscription->status === Subscription::STATUS_ACTIVE) {
            return view('payments.result', [
                'success' => true,
                'title' => 'Subscription active',
                'message' => 'Your AmaSports subscription is active. You can close this window and return to the app.',
            ]);
        }

        try {
            $order = $paypal->getOrder($orderId);

            if (($order['status'] ?? null) !== 'COMPLETED') {
                $order = $paypal->captureOrder($orderId);
            }

            if (($order['status'] ?? null) !== 'COMPLETED') {
                return view('payments.result', [
                    'success' => false,
                    'title' => 'Payment not completed',
                    'message' => 'PayPal has not confirmed this payment yet. Please close this window and check back in the app in a moment.',
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Subscription capture failed on return.', [
                'subscription_id' => $subscription->id,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return view('payments.result', [
                'success' => false,
                'title' => 'Something went wrong',
                'message' => 'We could not confirm your payment with PayPal. Please close this window and check back in the app in a moment, or try again.',
            ]);
        }

        $this->activate($subscription);

        return view('payments.result', [
            'success' => true,
            'title' => 'Subscription active',
            'message' => 'Payment received — your AmaSports subscription is now active for 1 year. You can close this window and return to the app.',
        ]);
    }

    /** GET /payments/subscriptions/cancel?token={paypal_order_id} */
    public function cancel(Request $request): View
    {
        $orderId = (string) $request->query('token');

        Subscription::where('paypal_order_id', $orderId)
            ->where('status', Subscription::STATUS_PENDING)
            ->update(['status' => Subscription::STATUS_CANCELLED]);

        return view('payments.result', [
            'success' => false,
            'title' => 'Checkout cancelled',
            'message' => 'You cancelled the payment. No charge was made — you can close this window and try again anytime from the app.',
        ]);
    }

    /**
     * Shared with the webhook handler (Api\PayPalWebhookController), which
     * may race this or arrive first — both paths are safe to call twice.
     */
    public static function activate(Subscription $subscription): void
    {
        if ($subscription->status === Subscription::STATUS_ACTIVE) {
            return;
        }

        $startsAt = now();

        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addYear(),
        ]);
    }
}
