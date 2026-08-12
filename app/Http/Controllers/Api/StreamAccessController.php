<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\LiveStreamAccess;
use App\Services\PayPalService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * The $5-per-match live-stream unlock, purchasable in-app by any player as
 * "VIP access" (Phase 6 revision 3) — not just the admin running the match
 * (see Admin\StreamAccessController for that original, still-standing
 * path). Access is match-scoped, not player-scoped: whoever pays first
 * unlocks the stream for every viewer of that match, same as the admin
 * flow — see LiveStreamAccess and GameMatch::hasActiveStreamAccess().
 */
class StreamAccessController extends Controller
{
    use ApiResponse;

    /**
     * POST /matches/{match}/stream-access/create-order — mirrors
     * Api\SubscriptionController::createOrder: creates a `pending` row,
     * then a matching PayPal order, and hands the mobile app the
     * PayPal-hosted approval URL to open in an in-app browser.
     */
    public function createOrder(Request $request, GameMatch $match, PayPalService $paypal): JsonResponse
    {
        if ($match->status === GameMatch::STATUS_FINISHED) {
            return $this->error("This match has finished — its live stream can't be unlocked anymore.", 422);
        }

        if ($match->hasActiveStreamAccess()) {
            return $this->error("This match's live stream is already unlocked.", 409);
        }

        if (! $paypal->isConfigured()) {
            return $this->error('Payments are not configured yet. Please try again later.', 503);
        }

        $currency = config('services.paypal.currency');

        $access = LiveStreamAccess::create([
            'match_id' => $match->id,
            'paid_by' => $request->user()->id,
            'amount' => LiveStreamAccess::AMOUNT,
            'currency' => $currency,
            'status' => LiveStreamAccess::STATUS_PENDING,
        ]);

        try {
            $order = $paypal->createOrder(
                amount: LiveStreamAccess::AMOUNT,
                currency: $currency,
                customId: "stream_access:{$access->id}",
                description: config('app.name')." VIP Live Stream Access — Match #{$match->id}",
                returnUrl: route('payments.stream-access.return'),
                cancelUrl: route('payments.stream-access.cancel'),
            );
        } catch (Throwable $e) {
            Log::error('Failed to create PayPal order for live-stream access.', [
                'match_id' => $match->id,
                'access_id' => $access->id,
                'message' => $e->getMessage(),
            ]);

            return $this->error('Could not start checkout with PayPal. Please try again.', 502);
        }

        $access->update(['paypal_order_id' => $order['id']]);

        $approveUrl = $paypal->approveUrl($order);
        if (! $approveUrl) {
            return $this->error('PayPal did not return a checkout link. Please try again.', 502);
        }

        return $this->success([
            'access_id' => $access->id,
            'order_id' => $order['id'],
            'approve_url' => $approveUrl,
        ], 'Checkout order created.');
    }
}
