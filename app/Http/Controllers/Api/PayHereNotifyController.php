<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payments\StreamAccessPaymentController;
use App\Http\Controllers\Payments\SubscriptionPaymentController;
use App\Models\Subscription;
use App\Services\PayHereService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * POST /api/payhere/notify — PayHere's server-to-server payment result
 * (form-encoded), and the source of truth for every payment in the app:
 * subscriptions and live-stream unlocks alike (told apart by the order_id
 * prefix — see PayHereService::findPayable()). return_url pages only show
 * the payer something; this is what actually activates access.
 *
 * Every handler here is idempotent (PayHere may send more than one
 * notification per order, e.g. pending then success).
 */
class PayHereNotifyController extends Controller
{
    public function handle(Request $request, PayHereService $payhere): Response
    {
        if (! $payhere->verifyNotification($request)) {
            Log::warning('Rejected PayHere notification — signature verification failed.', [
                'order_id' => $request->input('order_id'),
            ]);

            return response('Invalid signature.', 400);
        }

        $orderId = (string) $request->input('order_id');
        $statusCode = (int) $request->input('status_code');
        $payable = $payhere->findPayable($orderId);

        if (! $payable) {
            Log::warning("PayHere notification for unknown order {$orderId}.");

            return response('OK');
        }

        if (! $payhere->amountMatches($payable, $request->input('payhere_amount'), $request->input('payhere_currency'))) {
            Log::error("PayHere notification amount mismatch for order {$orderId} — not activating.", [
                'expected' => [$payable->amount, $payable->currency],
                'received' => [$request->input('payhere_amount'), $request->input('payhere_currency')],
            ]);

            return response('OK');
        }

        $paymentId = $request->input('payment_id');
        $method = $request->input('method');

        match ($statusCode) {
            PayHereService::STATUS_SUCCESS => $payable instanceof Subscription
                ? SubscriptionPaymentController::activate($payable, $paymentId, $method)
                : StreamAccessPaymentController::activate($payable, $paymentId, $method),

            // Cancelled/failed: only ever close out a still-pending row.
            PayHereService::STATUS_CANCELED, PayHereService::STATUS_FAILED => $payable->status === $payable::STATUS_PENDING
                ? $payable->update(['status' => $payable::STATUS_CANCELLED])
                : null,

            // Chargeback: the money was pulled back, so revoke access.
            PayHereService::STATUS_CHARGEDBACK => $this->revoke($payable, $orderId),

            default => null, // 0 = pending — wait for the next notification.
        };

        return response('OK');
    }

    private function revoke($payable, string $orderId): void
    {
        Log::warning("PayHere chargeback for order {$orderId} — revoking access.");

        $payable->update(['status' => $payable::STATUS_CANCELLED]);
    }
}
