<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payments\SubscriptionPaymentController;
use App\Models\LiveStreamAccess;
use App\Models\Subscription;
use App\Services\PayPalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * POST /api/paypal/webhook — server-to-server source of truth backing up
 * the return-URL capture flow (SubscriptionPaymentController,
 * Admin\StreamAccessController). Handles both order types via the
 * `custom_id` set at order creation ("subscription:{id}" or
 * "stream_access:{id}") since a single webhook endpoint is registered in
 * the PayPal dashboard for the whole app.
 *
 * Every handler here is idempotent (PayPal retries undelivered webhooks)
 * and this endpoint always returns 200 once the signature is verified —
 * even for event types we don't act on — so PayPal doesn't keep retrying
 * events we've already deliberately ignored.
 */
class PayPalWebhookController extends Controller
{
    /** Event types that carry a `custom_id` we can act on. */
    private const HANDLED_EVENTS = [
        'CHECKOUT.ORDER.APPROVED',
        'PAYMENT.CAPTURE.COMPLETED',
    ];

    public function handle(Request $request, PayPalService $paypal): JsonResponse
    {
        if (! $paypal->verifyWebhookSignature($request)) {
            Log::warning('Rejected PayPal webhook — signature verification failed.', [
                'event_type' => $request->input('event_type'),
            ]);

            return response()->json(['success' => false, 'message' => 'Invalid signature.'], 400);
        }

        $eventType = $request->input('event_type');

        if (! in_array($eventType, self::HANDLED_EVENTS, true)) {
            return response()->json(['success' => true, 'message' => 'Event ignored.']);
        }

        $customId = $this->resolveCustomId($request);

        if (! $customId || ! str_contains($customId, ':')) {
            Log::info('PayPal webhook missing a recognizable custom_id — ignoring.', ['event_type' => $eventType]);

            return response()->json(['success' => true, 'message' => 'No matching order.']);
        }

        [$type, $id] = explode(':', $customId, 2);

        match ($type) {
            'subscription' => $this->handleSubscription((int) $id),
            'stream_access' => $this->handleStreamAccess((int) $id),
            default => Log::info("PayPal webhook custom_id has an unrecognized type '{$type}'."),
        };

        return response()->json(['success' => true]);
    }

    /**
     * `custom_id` lives at `resource.purchase_units[0].custom_id` on
     * CHECKOUT.ORDER.* events, but capture events flatten it straight onto
     * `resource.custom_id`.
     */
    private function resolveCustomId(Request $request): ?string
    {
        return $request->input('resource.custom_id')
            ?? $request->input('resource.purchase_units.0.custom_id');
    }

    private function handleSubscription(int $subscriptionId): void
    {
        $subscription = Subscription::find($subscriptionId);

        if (! $subscription) {
            Log::warning("PayPal webhook referenced unknown subscription #{$subscriptionId}.");

            return;
        }

        // Idempotent — shares the exact same "already active? skip" guard
        // as the return-URL flow, whichever of the two arrives first wins.
        SubscriptionPaymentController::activate($subscription);
    }

    private function handleStreamAccess(int $accessId): void
    {
        $access = LiveStreamAccess::find($accessId);

        if (! $access) {
            Log::warning("PayPal webhook referenced unknown live_stream_access #{$accessId}.");

            return;
        }

        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return;
        }

        $access->update([
            'status' => LiveStreamAccess::STATUS_ACTIVE,
            'purchased_at' => $access->purchased_at ?? now(),
        ]);
    }
}
