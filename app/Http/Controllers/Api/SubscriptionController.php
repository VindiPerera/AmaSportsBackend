<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use App\Models\SubscriptionCountryPrice;
use App\Services\PayHereService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Creates a `pending` row and hands the mobile app a signed link to our
     * PayHere checkout page (see PayHereService) to open in-app. Activation
     * happens server-side when PayHere calls notify_url; the app just polls
     * subscription-status once the checkout view closes.
     */
    public function createOrder(Request $request, PayHereService $payhere): JsonResponse
    {
        if (! $payhere->isConfigured()) {
            return $this->error('Payments are not configured yet. Please try again later.', 503);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        if (! $player->canPurchaseSubscription()) {
            return $this->error(
                'Your 1-year plan is already active. You can renew it in the last '.Subscription::RENEWAL_WINDOW_DAYS.' days before it ends.',
                422
            );
        }

        $subscription = Subscription::create([
            'player_id' => $player->id,
            'amount' => SubscriptionCountryPrice::amountFor($player->country),
            'currency' => $payhere->currency(),
            'status' => Subscription::STATUS_PENDING,
        ]);

        $orderId = $payhere->newOrderId(PayHereService::PREFIX_SUBSCRIPTION, $subscription->id);
        $subscription->update(['payment_order_id' => $orderId]);

        return $this->success([
            'subscription_id' => $subscription->id,
            'order_id' => $orderId,
            'approve_url' => $payhere->checkoutUrl(
                orderId: $orderId,
                items: config('app.name').' Annual Subscription',
                returnUrl: route('payments.subscriptions.return'),
                cancelUrl: route('payments.subscriptions.cancel'),
            ),
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
        // The row granting access right now (see Player::currentSubscription())
        // — only falls back to the latest row when nothing is active, so an
        // abandoned upgrade/renewal checkout never masks a running plan.
        $subscription = $player->currentSubscription() ?? $player->latestSubscription();
        // What THIS player would pay right now — only relevant as a preview
        // for the two branches below (no active/pending subscription yet);
        // once a real subscription row exists its own stored `amount` is
        // what was actually charged and must never be second-guessed here.
        $previewAmount = SubscriptionCountryPrice::amountFor($player->country);

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
                'amount' => $previewAmount,
                'currency' => config('services.payhere.currency'),
                'plan_amount' => $previewAmount,
                'can_purchase' => $player->canPurchaseSubscription(),
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
                'amount' => $previewAmount,
                'currency' => config('services.payhere.currency'),
                'plan_amount' => $previewAmount,
                'can_purchase' => $player->canPurchaseSubscription(),
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
            // What the 1-year plan costs this player now (country price) —
            // `amount` above is what the reported row was charged, which is
            // 0 for the free trial.
            'plan_amount' => $previewAmount,
            'can_purchase' => $player->canPurchaseSubscription(),
        ], 'Subscription status retrieved successfully.');
    }

    /**
     * POST /subscriptions/start-trial — the one-time free first 10 days
     * (Phase 8). No PayHere order: unlocks immediately. Re-validates
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
            'currency' => config('services.payhere.currency'),
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
            'plan_amount' => SubscriptionCountryPrice::amountFor($player->country),
            'can_purchase' => true,
        ], 'Your free trial has started.');
    }

    /**
     * GET /subscription-prices — every admin-configured country price, plus
     * the default, for the mobile app's country-selection screen (see
     * Frontend app/(protected)/select-country.tsx) to preview a price for
     * whichever country the player has highlighted before they've actually
     * saved it to their profile yet. Country names match
     * Frontend/src/constants/countries.ts exactly (see config/countries.php).
     */
    public function prices(): JsonResponse
    {
        return $this->success([
            'default_amount' => Subscription::AMOUNT,
            'currency' => config('services.payhere.currency'),
            'prices' => SubscriptionCountryPrice::allAsMap(),
        ], 'Subscription prices retrieved successfully.');
    }
}
