<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Subscription;
use App\Models\SubscriptionCountryPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Plan selection (free 10-day trial vs 1-year plan), upgrading from the
 * trial to the paid year, and renewing — see Player::currentSubscription()
 * and SubscriptionPaymentController::activate().
 */
class SubscriptionPlansTest extends TestCase
{
    use RefreshDatabase;

    private const MERCHANT_SECRET = 'test-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.payhere.mode' => 'sandbox',
            'services.payhere.merchant_id' => '1211149',
            'services.payhere.merchant_secret' => self::MERCHANT_SECRET,
            'services.payhere.currency' => 'USD',
            'services.payhere.app_id' => null,
            'services.payhere.app_secret' => null,
            'subscription.bypass' => false,
        ]);
    }

    public function test_new_player_can_pick_either_plan(): void
    {
        $this->actingAsPlayer();

        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.trial_eligible', true)
            ->assertJsonPath('data.can_purchase', true)
            ->assertJsonPath('data.plan_amount', 10);
    }

    public function test_new_player_can_skip_trial_and_buy_the_year(): void
    {
        $this->actingAsPlayer();

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $this->paySuccessfully($order['order_id'], '10.00');

        $subscription = Subscription::find($order['subscription_id']);
        $this->assertFalse($subscription->is_trial);
        $this->assertEqualsWithDelta(now()->addYear()->timestamp, $subscription->expires_at->timestamp, 5);

        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.is_trial', false)
            ->assertJsonPath('data.can_purchase', false);
    }

    public function test_trial_player_can_upgrade_and_keeps_remaining_trial_days(): void
    {
        $this->actingAsPlayer();
        $trialEnds = now()->addDays(10);
        $this->postJson('/api/subscriptions/start-trial')->assertOk();

        // Two days into the trial: the upgrade is offered at the real price, not the trial's $0.
        $this->travel(2)->days();
        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.is_trial', true)
            ->assertJsonPath('data.amount', 0)
            ->assertJsonPath('data.plan_amount', 10)
            ->assertJsonPath('data.can_purchase', true);

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');

        // Pending checkout must not cut off the running trial.
        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.is_trial', true);

        $this->paySuccessfully($order['order_id'], '10.00');

        $paid = Subscription::find($order['subscription_id']);
        $this->assertEqualsWithDelta($trialEnds->timestamp, $paid->starts_at->timestamp, 5);
        $this->assertEqualsWithDelta($trialEnds->copy()->addYear()->timestamp, $paid->expires_at->timestamp, 5);

        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.is_trial', false)
            ->assertJsonPath('data.can_purchase', false);

        // Still unlocked after the trial would have ended.
        $this->travel(9)->days();
        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_active', true);
    }

    public function test_abandoned_upgrade_checkout_keeps_trial_access(): void
    {
        $this->actingAsPlayer();
        $this->postJson('/api/subscriptions/start-trial')->assertOk();
        $this->postJson('/api/subscriptions/create-order')->assertOk();

        $this->getJson('/api/player/cricket-analysis')->assertStatus(200);
        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_trial', true);
    }

    public function test_cannot_buy_again_while_year_has_months_left(): void
    {
        $player = $this->actingAsPlayer();
        $this->activeSubscription($player, now()->addMonths(6));

        $this->postJson('/api/subscriptions/create-order')->assertStatus(422);
        $this->assertSame(1, $player->subscriptions()->count());
    }

    public function test_early_renewal_adds_a_year_on_top_of_current_expiry(): void
    {
        $player = $this->actingAsPlayer();
        $current = $this->activeSubscription($player, now()->addDays(20));

        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.expiring_soon', true)
            ->assertJsonPath('data.can_purchase', true);

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $this->paySuccessfully($order['order_id'], '10.00');

        $renewal = Subscription::find($order['subscription_id']);
        $this->assertEqualsWithDelta($current->expires_at->timestamp, $renewal->starts_at->timestamp, 5);
        $this->assertEqualsWithDelta($current->expires_at->copy()->addYear()->timestamp, $renewal->expires_at->timestamp, 5);
    }

    public function test_expired_year_requires_payment_and_restarts_from_payment_date(): void
    {
        $player = $this->actingAsPlayer();
        $this->activeSubscription($player, now()->addDays(5));
        $this->travel(6)->days();

        $this->getJson('/api/player/subscription-status')
            ->assertJsonPath('data.is_active', false)
            ->assertJsonPath('data.has_subscribed', true)
            ->assertJsonPath('data.can_purchase', true);
        $this->getJson('/api/player/cricket-analysis')->assertStatus(402);

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $this->paySuccessfully($order['order_id'], '10.00');

        $renewal = Subscription::find($order['subscription_id']);
        $this->assertEqualsWithDelta(now()->addYear()->timestamp, $renewal->expires_at->timestamp, 5);
    }

    public function test_upgrade_uses_country_price(): void
    {
        SubscriptionCountryPrice::create(['country' => 'Sri Lanka', 'amount' => 4.00]);
        $player = $this->actingAsPlayer();
        $player->update(['country' => 'Sri Lanka']);
        $this->postJson('/api/subscriptions/start-trial')->assertOk();

        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.plan_amount', 4);

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $this->assertEquals(4.00, Subscription::find($order['subscription_id'])->amount);
    }

    public function test_trial_cannot_be_started_while_paid_year_is_active(): void
    {
        $player = $this->actingAsPlayer();
        $this->activeSubscription($player, now()->addMonths(6));

        $this->postJson('/api/subscriptions/start-trial')->assertStatus(422);
    }

    private function actingAsPlayer(): Player
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return Player::firstOrCreate(['user_id' => $user->id]);
    }

    private function activeSubscription(Player $player, $expiresAt): Subscription
    {
        return Subscription::create([
            'player_id' => $player->id,
            'amount' => 10,
            'currency' => 'USD',
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => $expiresAt->copy()->subYear(),
            'expires_at' => $expiresAt,
        ]);
    }

    private function paySuccessfully(string $orderId, string $amount): void
    {
        $this->post('/api/payhere/notify', [
            'merchant_id' => '1211149',
            'order_id' => $orderId,
            'payment_id' => '320025071278',
            'payhere_amount' => $amount,
            'payhere_currency' => 'USD',
            'status_code' => 2,
            'method' => 'VISA',
            'md5sig' => strtoupper(md5(
                '1211149'.$orderId.$amount.'USD'.'2'.strtoupper(md5(self::MERCHANT_SECRET))
            )),
        ])->assertOk();
    }
}
