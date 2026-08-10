<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

/**
 * Thin wrapper around the PayPal Orders API v2 (hosted checkout) and the
 * webhook signature verification endpoint. Shared by every payment flow in
 * the app — the $10/year player subscription (Api\SubscriptionController)
 * and the $5/match admin live-stream unlock (Admin\StreamAccessController)
 * — so the PayPal integration itself is written exactly once.
 *
 * Deliberately no card data ever touches this app: every checkout happens
 * on PayPal's own hosted page, we only create/capture orders server-side.
 */
class PayPalService
{
    public function isConfigured(): bool
    {
        return filled(config('services.paypal.client_id')) && filled(config('services.paypal.client_secret'));
    }

    /**
     * Create a CAPTURE-intent order for one `purchase_unit` and return
     * PayPal's raw decoded response (id, status, links[] incl. the
     * "approve" URL the payer needs to be sent to).
     *
     * @return array<string, mixed>
     */
    public function createOrder(
        float $amount,
        string $currency,
        string $customId,
        string $description,
        string $returnUrl,
        string $cancelUrl
    ): array {
        $response = $this->client()->post('/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'custom_id' => $customId,
                'description' => $description,
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'brand_name' => config('app.name'),
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ]);

        return $this->decodeOrThrow($response, 'create order');
    }

    /**
     * GET the current state of an order — used before capturing (idempotent
     * re-entry: if it's already COMPLETED, skip straight to marking our own
     * row active instead of capturing twice) and by status-check endpoints.
     *
     * @return array<string, mixed>
     */
    public function getOrder(string $orderId): array
    {
        $response = $this->client()->get("/v2/checkout/orders/{$orderId}");

        return $this->decodeOrThrow($response, 'fetch order');
    }

    /**
     * Capture payment for an approved order. Safe to call once per approved
     * order; PayPal returns 422 ORDER_ALREADY_CAPTURED on a repeat, which
     * callers should treat as success (see SubscriptionController /
     * StreamAccessController — always getOrder() first to check status).
     *
     * @return array<string, mixed>
     */
    public function captureOrder(string $orderId): array
    {
        $response = $this->client()->post("/v2/checkout/orders/{$orderId}/capture");

        return $this->decodeOrThrow($response, 'capture order');
    }

    /** Pull the payer-facing "approve" link out of a createOrder() response. */
    public function approveUrl(array $order): ?string
    {
        foreach ($order['links'] ?? [] as $link) {
            if (($link['rel'] ?? null) === 'approve') {
                return $link['href'];
            }
        }

        return null;
    }

    /**
     * Verify an inbound webhook actually came from PayPal before trusting
     * any event it carries — every webhook handler must call this first.
     * Fails closed: returns false (not an exception) on any transport
     * error or missing config, so a misconfigured webhook_id can never be
     * mistaken for a verified one.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');

        if (blank($webhookId)) {
            Log::warning('PayPal webhook received but PAYPAL_WEBHOOK_ID is not configured — rejecting.');

            return false;
        }

        try {
            $response = $this->client()->post('/v1/notifications/verify-webhook-signature', [
                'transmission_id' => $request->header('Paypal-Transmission-Id'),
                'transmission_time' => $request->header('Paypal-Transmission-Time'),
                'cert_url' => $request->header('Paypal-Cert-Url'),
                'auth_algo' => $request->header('Paypal-Auth-Algo'),
                'transmission_sig' => $request->header('Paypal-Transmission-Sig'),
                'webhook_id' => $webhookId,
                'webhook_event' => $request->json()->all(),
            ]);

            if ($response->failed()) {
                Log::warning('PayPal webhook signature verification request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return $response->json('verification_status') === 'SUCCESS';
        } catch (Throwable $e) {
            Log::warning('PayPal webhook signature verification threw.', ['message' => $e->getMessage()]);

            return false;
        }
    }

    private function client()
    {
        return Http::baseUrl($this->apiBase())
            ->withToken($this->accessToken())
            ->acceptJson()
            ->asJson()
            ->timeout(15);
    }

    private function apiBase(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /** Client-credentials OAuth2 token, cached just under its ~9hr lifetime. */
    private function accessToken(): string
    {
        return Cache::remember('paypal_access_token_'.config('services.paypal.mode'), now()->addMinutes(50), function () {
            $response = Http::baseUrl($this->apiBase())
                ->asForm()
                ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.client_secret'))
                ->timeout(15)
                ->post('/v1/oauth2/token', ['grant_type' => 'client_credentials']);

            if ($response->failed()) {
                Log::error('PayPal OAuth token request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new RuntimeException('Could not authenticate with PayPal.');
            }

            return $response->json('access_token');
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeOrThrow($response, string $action): array
    {
        if ($response->failed()) {
            Log::error("PayPal {$action} failed.", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException("Could not {$action} with PayPal. Please try again.");
        }

        return $response->json();
    }
}
