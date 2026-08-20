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
     * browser (WebBrowser.openAuthSessionAsync — capture still happens
     * server-side on PayPal's return_url page below, same as before; the
     * return page just also bounces the browser to the app's deep link
     * afterward, which is what lets openAuthSessionAsync auto-close the
     * sheet instead of the player having to dismiss it manually).
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
     * displays. `is_active`/`status`/`expires_at` etc. are resolved from
     * the *latest* subscription row, but `has_subscribed` is not — see
     * Player::hasEverBeenSubscribed(). Also tells the mobile app which
     * paywall variant to show (Phase 8): `is_trial` labels the *current*
     * active subscription, `trial_eligible` is independent of it — a
     * lapsed trial leaves `trial_eligible` false but `is_trial` moot
     * (nothing is active).
     */
    public function status(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $subscription = $player->latestSubscription();

        // Dev-only escape hatch — see config/subscription.php. Reports as
        // subscribed/active so the mobile paywall never blocks Add Sport /
        // Analysis locally, without touching the real subscription data.
        if (config('subscription.bypass')) {
            return $this->success([
                'has_subscribed' => true,
                'status' => Subscription::STATUS_ACTIVE,
                'is_active' => true,
                'is_trial' => false,
                'trial_eligible' => false,
                'starts_at' => $subscription?->starts_at?->toISOString(),
                'expires_at' => null,
                'days_remaining' => null,
                'expiring_soon' => false,
                'amount' => Subscription::AMOUNT,
                'currency' => config('services.paypal.currency'),
            ], 'Subscription status retrieved successfully.');
        }

        if (! $subscription) {
            return $this->success([
                'has_subscribed' => false,
                'status' => 'none',
                'is_active' => false,
                'is_trial' => false,
                'trial_eligible' => $player->isTrialEligible(),
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
            // Not just "a row exists" — see Player::hasEverBeenSubscribed().
            // The latest row can be a `pending` checkout that was started
            // and abandoned/failed, which must never read as "expired".
            'has_subscribed' => $player->hasEverBeenSubscribed(),
            'status' => $subscription->status,
            'is_active' => $isActive,
            'is_trial' => $isActive && $subscription->is_trial,
            'trial_eligible' => $player->isTrialEligible(),
            'starts_at' => $subscription->starts_at?->toISOString(),
            'expires_at' => $subscription->expires_at?->toISOString(),
            'days_remaining' => $daysRemaining !== null ? (int) $daysRemaining : null,
            'expiring_soon' => $isActive && $daysRemaining !== null && $daysRemaining <= 30,
            'amount' => (float) $subscription->amount,
            'currency' => $subscription->currency,
        ], 'Subscription status retrieved successfully.');
    }

    /**
     * POST /subscriptions/start-trial — the one-time free first 10 days
     * (Phase 8). No PayPal order: unlocks immediately. Re-validates
     * eligibility server-side regardless of what the UI shows, since a
     * stale client (or a direct API call) could otherwise let a player
     * double-dip.
     */
    public function startTrial(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        if (! $player->isTrialEligible()) {
            return $this->error("You've already used your free trial.", 422);
        }

        if ($player->hasActiveSubscription()) {
            return $this->error('You already have an active subscription.', 422);
        }

        // Server time throughout — never trust the client's clock for
        // trial start/expiry.
        $startsAt = now();

        $subscription = Subscription::create([
            'player_id' => $player->id,
            'amount' => 0,
            'currency' => config('services.paypal.currency'),
            'status' => Subscription::STATUS_ACTIVE,
            'is_trial' => true,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addDays(10),
        ]);

        // forceFill, not fillable — trial_used_at is deliberately not mass
        // assignable (see Player::isTrialEligible()); this is the one place
        // allowed to set it.
        $player->forceFill(['trial_used_at' => $startsAt])->save();

        $daysRemaining = (int) now()->diffInDays($subscription->expires_at, false);

        return $this->success([
            'has_subscribed' => true,
            'status' => $subscription->status,
            'is_active' => true,
            'is_trial' => true,
            'trial_eligible' => false,
            'starts_at' => $subscription->starts_at?->toISOString(),
            'expires_at' => $subscription->expires_at?->toISOString(),
            'days_remaining' => $daysRemaining,
            'expiring_soon' => $daysRemaining <= 30,
            'amount' => (float) $subscription->amount,
            'currency' => $subscription->currency,
        ], 'Your free trial has started.');
    }
}
