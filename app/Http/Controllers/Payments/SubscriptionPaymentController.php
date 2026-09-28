<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PayHereService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Plain server-rendered pages PayHere redirects the payer's browser to after
 * hosted checkout — hit directly by the browser, not by the mobile app, so
 * these stay outside the Sanctum-protected /api surface (routes/web.php).
 *
 * Neither page activates anything based on the redirect alone: PayHere's
 * notify_url (Api\PayHereNotifyController) is the source of truth, and the
 * mobile app polls GET /api/player/subscription-status itself once the
 * checkout view closes. These pages exist to (a) give the payer something
 * to look at, (b) confirm early via the optional PayHere Retrieval API when
 * notify_url hasn't landed yet, and (c) bounce the browser to the app's
 * deep link scheme afterward (see config('subscription.mobile_return_scheme')
 * / result.blade.php) so the in-app checkout view closes.
 */
class SubscriptionPaymentController extends Controller
{
    /** GET /payments/subscriptions/return?order_id={payment_order_id} */
    public function return(Request $request, PayHereService $payhere): View
    {
        $subscription = Subscription::where('payment_order_id', (string) $request->query('order_id'))->first();

        if (! $subscription) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Payment not found',
                'message' => "We couldn't find that order. If you were charged, please contact support.",
            ]);
        }

        if ($subscription->status !== Subscription::STATUS_ACTIVE && ($payment = $payhere->findReceivedPayment($subscription))) {
            self::activate($subscription, $payment['payment_id'] ?? null, $payment['payment_method']['method'] ?? null);
        }

        if ($subscription->status === Subscription::STATUS_ACTIVE) {
            return view('payments.result', [
                'success' => true,
                'title' => 'Subscription active',
                'message' => 'Payment received — your AmaSports subscription is now active for 1 year. You can close this window and return to the app.',
            ]);
        }

        return view('payments.result', [
            'success' => true,
            'title' => 'Payment submitted',
            'message' => "We're confirming your payment with PayHere — your subscription will activate in a few seconds. You can return to the app.",
        ]);
    }

    /** GET /payments/subscriptions/cancel?order_id={payment_order_id} */
    public function cancel(Request $request): View
    {
        Subscription::where('payment_order_id', (string) $request->query('order_id'))
            ->where('status', Subscription::STATUS_PENDING)
            ->update(['status' => Subscription::STATUS_CANCELLED]);

        return view('payments.result', [
            'success' => false,
            'title' => 'Checkout cancelled',
            'message' => 'You cancelled the payment. No charge was made — you can close this window and try again anytime from the app.',
        ]);
    }

    /**
     * Shared with PayHere's notify handler (Api\PayHereNotifyController),
     * which may race the return page or arrive first — safe to call twice.
     */
    public static function activate(Subscription $subscription, ?string $paymentId = null, ?string $method = null): void
    {
        if ($subscription->status === Subscription::STATUS_ACTIVE) {
            return;
        }

        $startsAt = now();

        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'payment_reference' => $paymentId ?? $subscription->payment_reference,
            'payment_method' => $method ?? $subscription->payment_method,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addYear(),
        ]);
    }
}
