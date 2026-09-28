<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\LiveStreamAccess;
use App\Services\PayHereService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Plain server-rendered pages PayHere redirects the payer's browser to after
 * hosted checkout for a player-initiated "VIP" live-stream unlock — mirrors
 * Payments\SubscriptionPaymentController exactly; see that class for why
 * these stay outside the Sanctum-protected /api surface and why nothing is
 * activated from the redirect alone (the mobile app polls GET /matches/{id}
 * for `stream_access_active` itself once the in-app browser closes).
 */
class StreamAccessPaymentController extends Controller
{
    /** GET /payments/stream-access/return?order_id={payment_order_id} */
    public function return(Request $request, PayHereService $payhere): View
    {
        $access = LiveStreamAccess::where('payment_order_id', (string) $request->query('order_id'))->first();

        if (! $access) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Payment not found',
                'message' => "We couldn't find that order. If you were charged, please contact support.",
            ]);
        }

        if ($access->status !== LiveStreamAccess::STATUS_ACTIVE && ($payment = $payhere->findReceivedPayment($access))) {
            self::activate($access, $payment['payment_id'] ?? null, $payment['payment_method']['method'] ?? null);
        }

        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return view('payments.result', [
                'success' => true,
                'title' => 'Live stream unlocked',
                'message' => "Payment received — this match's live stream is now unlocked for everyone. You can close this window and return to the app.",
            ]);
        }

        return view('payments.result', [
            'success' => true,
            'title' => 'Payment submitted',
            'message' => "We're confirming your payment with PayHere — the stream will unlock in a few seconds. You can return to the app.",
        ]);
    }

    /** GET /payments/stream-access/cancel?order_id={payment_order_id} */
    public function cancel(Request $request): View
    {
        LiveStreamAccess::where('payment_order_id', (string) $request->query('order_id'))
            ->where('status', LiveStreamAccess::STATUS_PENDING)
            ->update(['status' => LiveStreamAccess::STATUS_CANCELLED]);

        return view('payments.result', [
            'success' => false,
            'title' => 'Checkout cancelled',
            'message' => 'You cancelled the payment. No charge was made — you can close this window and try again anytime from the app.',
        ]);
    }

    /**
     * Shared with PayHere's notify handler (Api\PayHereNotifyController) and
     * the admin return page — safe to call twice, whichever arrives first wins.
     */
    public static function activate(LiveStreamAccess $access, ?string $paymentId = null, ?string $method = null): void
    {
        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return;
        }

        $access->update([
            'status' => LiveStreamAccess::STATUS_ACTIVE,
            'payment_reference' => $paymentId ?? $access->payment_reference,
            'payment_method' => $method ?? $access->payment_method,
            'purchased_at' => $access->purchased_at ?? now(),
        ]);
    }
}
