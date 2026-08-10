<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use App\Services\PayPalService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

/**
 * The $10/year app subscription that unlocks "Add Sport" and the Analysis
 * tab (Phase 6 revision 2). Mobile-only surface — see Admin\StreamAccessController
 * for the separate $5/match live-stream payment.
 */
class SubscriptionController extends Controller
{
    use ApiResponse;

    /**
     * POST /subscriptions/create-order — starts (or renews) a subscription.
     * Creates a `pending` row, then a matching PayPal order, and hands the
     * mobile app the PayPal-hosted approval URL to open in an in-app
     * browser (WebBrowser.openBrowserAsync — deliberately not an auth
     * session, since capture happens on PayPal's return_url page, not via a
     * deep-link the app has to catch).
     */
    public function createOrder(Request $request, PayPalService $paypal): JsonResponse
    {
        if (! $paypal->isConfigured()) {
            return $this->error('Payments are not configured yet. Please try again later.', 503);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $currency = config('services.paypal.currency');

        $subscription = Subscription::create([
            'player_id' => $player->id,
            'amount' => Subscription::AMOUNT,
            'currency' => $currency,
            'status' => Subscription::STATUS_PENDING,
        ]);

        try {
            $order = $paypal->createOrder(
                amount: Subscription::AMOUNT,
                currency: $currency,
                customId: "subscription:{$subscription->id}",
                description: config('app.name').' Annual Subscription',
                returnUrl: route('payments.subscriptions.return'),
                cancelUrl: route('payments.subscriptions.cancel'),
            );
        } catch (Throwable $e) {
            Log::error('Failed to create PayPal order for subscription.', [
                'subscription_id' => $subscription->id,
                'message' => $e->getMessage(),
            ]);

            return $this->error('Could not start checkout with PayPal. Please try again.', 502);
        }

        $subscription->update(['paypal_order_id' => $order['id']]);

        $approveUrl = $paypal->approveUrl($order);
        if (! $approveUrl) {
            return $this->error('PayPal did not return a checkout link. Please try again.', 502);
        }

        return $this->success([
            'subscription_id' => $subscription->id,
            'order_id' => $order['id'],
            'approve_url' => $approveUrl,
        ], 'Checkout order created.');
    }

    /**
     * GET /player/subscription-status — drives both the paywall gate
     * ("can I add a sport / open Analysis?") and the Profile/Home status
     * displays. Always resolved from the *latest* subscription row.
     */
    public function status(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $subscription = $player->latestSubscription();

        if (! $subscription) {
            return $this->success([
                'has_subscribed' => false,
                'status' => 'none',
                'is_active' => false,
                'starts_at' => null,
                'expires_at' => null,
                'days_remaining' => null,
                'expiring_soon' => false,
                'amount' => Subscription::AMOUNT,
                'currency' => config('services.paypal.currency'),
            ], 'Subscription status retrieved successfully.');
        }

        $isActive = $subscription->isActive();
        $daysRemaining = $isActive ? now()->diffInDays($subscription->expires_at, false) : null;

        return $this->success([
            'has_subscribed' => true,
            'status' => $subscription->status,
            'is_active' => $isActive,
            'starts_at' => $subscription->starts_at?->toISOString(),
            'expires_at' => $subscription->expires_at?->toISOString(),
            'days_remaining' => $daysRemaining !== null ? (int) $daysRemaining : null,
            'expiring_soon' => $isActive && $daysRemaining !== null && $daysRemaining <= 30,
            'amount' => (float) $subscription->amount,
            'currency' => $subscription->currency,
        ], 'Subscription status retrieved successfully.');
    }
}
