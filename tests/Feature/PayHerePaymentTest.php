<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PayHerePaymentTest extends TestCase
{
    use RefreshDatabase;

    private const MERCHANT_ID = '1211149';

    private const MERCHANT_SECRET = 'test-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.payhere.mode' => 'sandbox',
            'services.payhere.merchant_id' => self::MERCHANT_ID,
            'services.payhere.merchant_secret' => self::MERCHANT_SECRET,
            'services.payhere.currency' => 'USD',
            'services.payhere.app_id' => null,
            'services.payhere.app_secret' => null,
            'subscription.bypass' => false,
        ]);
    }

    public function test_full_subscription_flow_activates_on_valid_notify(): void
    {
        Sanctum::actingAs(User::factory()->create(['name' => 'Kasun Perera']));

        $order = $this->postJson('/api/subscriptions/create-order')->assertOk()->json('data');
        $this->assertStringStartsWith('SUB-', $order['order_id']);

        // Signed checkout page renders PayHere's form with a correct hash.
        $page = $this->get($order['approve_url'])->assertOk();
        $page->assertSee('https://sandbox.payhere.lk/pay/checkout', false);
        $page->assertSee('value="'.$order['order_id'].'"', false);
        $page->assertSee('value="10.00"', false);
        $page->assertSee('value="Kasun"', false);
        $page->assertSee('value="'.$this->checkoutHash($order['order_id'], '10.00', 'USD').'"', false);

        // Return page alone never activates.
        $this->get('/payments/subscriptions/return?order_id='.$order['order_id'])->assertOk()->assertSee('Payment submitted');
        $this->assertSame(Subscription::STATUS_PENDING, Subscription::find($order['subscription_id'])->status);

        $this->post('/api/payhere/notify', $this->notifyPayload($order['order_id'], '10.00', 'USD', 2))->assertOk();

        $subscription = Subscription::find($order['subscription_id']);
        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame('320025071278', $subscription->payment_reference);
        $this->assertSame('VISA', $subscription->payment_method);
        $this->assertNotNull($subscription->expires_at);

        $this->getJson('/api/player/subscription-status')->assertJsonPath('data.is_active', true);

        // Stale checkout link can't re-open checkout for a paid row.
        $this->get($order['approve_url'])->assertOk()->assertSee('Already paid');
    }

    public function test_notify_with_bad_signature_is_rejected(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = $this->postJson('/api/subscriptions/create-order')->json('data');

        $payload = $this->notifyPayload($order['order_id'], '10.00', 'USD', 2);
        $payload['md5sig'] = strtoupper(md5('forged'));

        $this->post('/api/payhere/notify', $payload)->assertStatus(400);
        $this->assertSame(Subscription::STATUS_PENDING, Subscription::find($order['subscription_id'])->status);
    }

    public function test_notify_with_wrong_amount_does_not_activate(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = $this->postJson('/api/subscriptions/create-order')->json('data');

        $this->post('/api/payhere/notify', $this->notifyPayload($order['order_id'], '1.00', 'USD', 2))->assertOk();

        $this->assertSame(Subscription::STATUS_PENDING, Subscription::find($order['subscription_id'])->status);
    }

    public function test_failed_notify_and_cancel_close_out_pending_rows(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $failed = $this->postJson('/api/subscriptions/create-order')->json('data');
        $this->post('/api/payhere/notify', $this->notifyPayload($failed['order_id'], '10.00', 'USD', -2))->assertOk();
        $this->assertSame(Subscription::STATUS_CANCELLED, Subscription::find($failed['subscription_id'])->status);

        $cancelled = $this->postJson('/api/subscriptions/create-order')->json('data');
        $this->get('/payments/subscriptions/cancel?order_id='.$cancelled['order_id'])->assertOk()->assertSee('Checkout cancelled');
        $this->assertSame(Subscription::STATUS_CANCELLED, Subscription::find($cancelled['subscription_id'])->status);
    }

    public function test_tampered_checkout_link_is_refused(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = $this->postJson('/api/subscriptions/create-order')->json('data');

        $this->get(str_replace('return=', 'return=https%3A%2F%2Fevil.example%2F', $order['approve_url']))
            ->assertOk()
            ->assertSee('Checkout link expired');
    }

    public function test_return_page_confirms_early_via_retrieval_api(): void
    {
        config(['services.payhere.app_id' => 'app', 'services.payhere.app_secret' => 'secret']);
        Sanctum::actingAs(User::factory()->create());
        $order = $this->postJson('/api/subscriptions/create-order')->json('data');

        Http::fake([
            'sandbox.payhere.lk/merchant/v1/oauth/token' => Http::response(['access_token' => 'tok']),
            'sandbox.payhere.lk/merchant/v1/payment/search*' => Http::response([
                'status' => 1,
                'data' => [[
                    'payment_id' => 320025071278,
                    'order_id' => $order['order_id'],
                    'status' => 'RECEIVED',
                    'currency' => 'USD',
                    'amount' => 10,
                    'payment_method' => ['method' => 'MASTER'],
                ]],
            ]),
        ]);

        $this->get('/payments/subscriptions/return?order_id='.$order['order_id'])->assertOk()->assertSee('Subscription active');
        $this->assertSame(Subscription::STATUS_ACTIVE, Subscription::find($order['subscription_id'])->status);
    }

    private function checkoutHash(string $orderId, string $amount, string $currency): string
    {
        return strtoupper(md5(self::MERCHANT_ID.$orderId.$amount.$currency.strtoupper(md5(self::MERCHANT_SECRET))));
    }

    /** @return array<string, mixed> */
    private function notifyPayload(string $orderId, string $amount, string $currency, int $statusCode): array
    {
        return [
            'merchant_id' => self::MERCHANT_ID,
            'order_id' => $orderId,
            'payment_id' => '320025071278',
            'payhere_amount' => $amount,
            'payhere_currency' => $currency,
            'status_code' => (string) $statusCode,
            'method' => 'VISA',
            'md5sig' => strtoupper(md5(
                self::MERCHANT_ID.$orderId.$amount.$currency.$statusCode.strtoupper(md5(self::MERCHANT_SECRET))
            )),
        ];
    }
}
