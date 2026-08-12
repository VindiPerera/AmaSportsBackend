<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\LiveStreamAccess;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * Plain server-rendered pages PayPal redirects the payer's browser to after
 * hosted checkout for a player-initiated $5 "VIP" live-stream unlock —
 * mirrors Payments\SubscriptionPaymentController exactly; see that class
 * for why these stay outside the Sanctum-protected /api surface and why
 * the mobile app doesn't rely on this redirect actually happening (it
 * polls GET /matches/{id} for `stream_access_active` itself once the
 * in-app browser closes — see Api\StreamAccessController).
 */
class StreamAccessPaymentController extends Controller
{
    /** GET /payments/stream-access/return?token={paypal_order_id}&PayerID=... */
    public function return(Request $request, PayPalService $paypal): View
    {
        $orderId = (string) $request->query('token');
        $access = LiveStreamAccess::where('paypal_order_id', $orderId)->first();

        if (! $access) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Payment not found',
                'message' => "We couldn't find that order. If you were charged, please contact support.",
            ]);
        }

        // Idempotent: a webhook or an earlier hit on this same return URL
        // may have already activated it (PayPal can retry/re-open this
        // page). Never re-capture or overwrite an already-settled row.
        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return view('payments.result', [
                'success' => true,
                'title' => 'Live stream unlocked',
                'message' => "This match's live stream is unlocked. You can close this window and return to the app.",
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
            Log::error('Live-stream access capture failed on return.', [
                'access_id' => $access->id,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return view('payments.result', [
                'success' => false,
                'title' => 'Something went wrong',
                'message' => 'We could not confirm your payment with PayPal. Please close this window and check back in the app in a moment, or try again.',
            ]);
        }

        self::activate($access);

        return view('payments.result', [
            'success' => true,
            'title' => 'Live stream unlocked',
            'message' => "Payment received — this match's live stream is now unlocked for everyone. You can close this window and return to the app.",
        ]);
    }

    /** GET /payments/stream-access/cancel?token={paypal_order_id} */
    public function cancel(Request $request): View
    {
        $orderId = (string) $request->query('token');

        LiveStreamAccess::where('paypal_order_id', $orderId)
            ->where('status', LiveStreamAccess::STATUS_PENDING)
            ->update(['status' => LiveStreamAccess::STATUS_CANCELLED]);

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
    public static function activate(LiveStreamAccess $access): void
    {
        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return;
        }

        $access->update([
            'status' => LiveStreamAccess::STATUS_ACTIVE,
            'purchased_at' => $access->purchased_at ?? now(),
        ]);
    }
}
