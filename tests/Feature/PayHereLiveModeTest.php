<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * PAYHERE_MODE=live end to end, without real money: checkout must post to
 * PayHere's live host (never sandbox), notify_url must be the public HTTPS
 * APP_URL, and a correctly signed live notification activates the plan.
 * PayHere signs live notifications exactly like sandbox ones, so this is
 * the same code path a real payment takes.
 */
class PayHereLiveModeTest extends TestCase
{
    use RefreshDatabase;

    private const MERCHANT_ID = '1234567';

    private const MERCHANT_SECRET = 'live-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.url' => 'https://www.amaxsport.com',
            'services.payhere.mode' => 'live',
            'services.payhere.merchant_id' => self::MERCHANT_ID,
            'services.payhere.merchant_secret' => self::MERCHANT_SECRET,
            'services.payhere.currency' => 'USD',
            'services.payhere.app_id' => null,
            'services.payhere.app_secret' => null,
            'subscription.bypass' => false,
        ]);
        URL::forceRootUrl('https://www.amaxsport.com');
        URL::forceScheme('https');
    }

    public function test_live_checkout_posts_to_live_payhere_with_public_https_notify_url(): void
    {
        Sanctum::actingAs(User::factory()->create(['name' => 'Kasun Perera']));

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $page = $this->get($order['approve_url'])->assertOk();

        $page->assertSee('https://www.payhere.lk/pay/checkout', false);
        $page->assertDontSee('sandbox.payhere.lk', false);
        $page->assertSee('value="https://www.amaxsport.com/api/payhere/notify"', false);
        $page->assertSee('value="'.self::MERCHANT_ID.'"', false);
    }

    public function test_signed_live_payment_activates_and_bad_ones_do_not(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');

        // Signed with the wrong secret (e.g. sandbox secret left on the live server) -> rejected.
        $this->post('/api/payhere/notify', $this->payload($order['order_id'], '10.00', 2, 'sandbox-secret'))->assertStatus(400);
        // Correct signature but someone paid less -> not activated.
        $this->post('/api/payhere/notify', $this->payload($order['order_id'], '1.00', 2))->assertOk();
        $this->assertSame(Subscription::STATUS_PENDING, Subscription::find($order['subscription_id'])->status);

        // The real thing.
        $this->post('/api/payhere/notify', $this->payload($order['order_id'], '10.00', 2))->assertOk();
        $this->assertSame(Subscription::STATUS_ACTIVE, Subscription::find($order['subscription_id'])->status);
        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_active', true);

        // PayHere retries the same notification -> still one active year, nothing doubled.
        $expires = Subscription::find($order['subscription_id'])->expires_at;
        $this->post('/api/payhere/notify', $this->payload($order['order_id'], '10.00', 2))->assertOk();
        $this->assertEquals($expires, Subscription::find($order['subscription_id'])->expires_at);
    }

    /** @return array<string, string> */
    private function payload(string $orderId, string $amount, int $status, string $secret = self::MERCHANT_SECRET): array
    {
        return [
            'merchant_id' => self::MERCHANT_ID,
            'order_id' => $orderId,
            'payment_id' => '320025071278',
            'payhere_amount' => $amount,
            'payhere_currency' => 'USD',
            'status_code' => (string) $status,
            'method' => 'VISA',
            'md5sig' => strtoupper(md5(self::MERCHANT_ID.$orderId.$amount.'USD'.$status.strtoupper(md5($secret)))),
        ];
    }
}
