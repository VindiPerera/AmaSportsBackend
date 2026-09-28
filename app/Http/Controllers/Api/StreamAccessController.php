<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\LiveStreamAccess;
use App\Services\PayHereService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Api\SubscriptionController::createOrder: creates a `pending` row and
     * hands the mobile app a signed link to our PayHere checkout page.
     */
    public function createOrder(Request $request, GameMatch $match, PayHereService $payhere): JsonResponse
    {
        if ($match->status === GameMatch::STATUS_FINISHED) {
            return $this->error("This match has finished — its live stream can't be unlocked anymore.", 422);
        }

        if ($match->hasActiveStreamAccess()) {
            return $this->error("This match's live stream is already unlocked.", 409);
        }

        if (! $payhere->isConfigured()) {
            return $this->error('Payments are not configured yet. Please try again later.', 503);
        }

        $access = LiveStreamAccess::create([
            'match_id' => $match->id,
            'paid_by' => $request->user()->id,
            'amount' => LiveStreamAccess::AMOUNT,
            'currency' => $payhere->currency(),
            'status' => LiveStreamAccess::STATUS_PENDING,
        ]);

        $orderId = $payhere->newOrderId(PayHereService::PREFIX_STREAM_ACCESS, $access->id);
        $access->update(['payment_order_id' => $orderId]);

        return $this->success([
            'access_id' => $access->id,
            'order_id' => $orderId,
            'approve_url' => $payhere->checkoutUrl(
                orderId: $orderId,
                items: config('app.name')." VIP Live Stream Access - Match #{$match->id}",
                returnUrl: route('payments.stream-access.return'),
                cancelUrl: route('payments.stream-access.cancel'),
            ),
        ], 'Checkout order created.');
    }
}
