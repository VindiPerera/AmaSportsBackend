<?php

namespace App\Services;

use App\Models\LiveStreamAccess;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

/**
 * Thin wrapper around the PayHere Checkout API (hosted checkout) and the
 * optional Payment Retrieval API. Shared by every payment flow in the app —
 * the yearly player subscription (Api\SubscriptionController) and the
 * per-match live-stream unlock (Api\ and Admin\StreamAccessController) — so
 * the PayHere integration itself is written exactly once.
 *
 * Unlike a REST "create order" API, PayHere checkout is started by the
 * payer's browser POSTing a signed HTML form to payhere.lk. So every flow
 * hands the payer a signed link to our own /payments/payhere/checkout page
 * (PayHereCheckoutController), which renders that form and auto-submits it.
 * The authoritative "paid" signal is PayHere's server-to-server POST to
 * notify_url (Api\PayHereNotifyController) — return_url is only a browser
 * redirect and is never trusted on its own.
 *
 * Deliberately no card data ever touches this app: every checkout happens
 * on PayHere's own hosted page.
 */
class PayHereService
{
    /** Our order_id prefixes — how notify_url knows which table to look in. */
    public const PREFIX_SUBSCRIPTION = 'SUB';

    public const PREFIX_STREAM_ACCESS = 'STREAM';

    /** PayHere notify `status_code` values. */
    public const STATUS_SUCCESS = 2;

    public const STATUS_PENDING = 0;

    public const STATUS_CANCELED = -1;

    public const STATUS_FAILED = -2;

    public const STATUS_CHARGEDBACK = -3;

    public function isConfigured(): bool
    {
        return filled(config('services.payhere.merchant_id')) && filled(config('services.payhere.merchant_secret'));
    }

    public function currency(): string
    {
        return config('services.payhere.currency');
    }

    /** A fresh, unguessable-enough order_id for PayHere, e.g. "SUB-12-K3QX9A". */
    public function newOrderId(string $prefix, int $id): string
    {
        return "{$prefix}-{$id}-".Str::upper(Str::random(6));
    }

    /** Look a payable row back up from the order_id we sent PayHere. */
    public function findPayable(string $orderId): Subscription|LiveStreamAccess|null
    {
        if ($orderId === '') {
            return null;
        }

        return match (Str::before($orderId, '-')) {
            self::PREFIX_SUBSCRIPTION => Subscription::where('payment_order_id', $orderId)->first(),
            self::PREFIX_STREAM_ACCESS => LiveStreamAccess::where('payment_order_id', $orderId)->first(),
            default => null,
        };
    }

    /**
     * The link the payer is sent to — our own auto-submitting form page.
     * Signed (relative, so it survives proxies/tunnels rewriting the host
     * or scheme) so the return/cancel URLs and item description riding in
     * the query string can't be tampered with. Amount/currency are never
     * in the URL at all: the checkout page reads them from the DB row.
     */
    public function checkoutUrl(string $orderId, string $items, string $returnUrl, string $cancelUrl): string
    {
        return url(URL::temporarySignedRoute('payments.payhere.checkout', now()->addHours(2), [
            'order' => $orderId,
            'items' => $items,
            'return' => $returnUrl,
            'cancel' => $cancelUrl,
        ], absolute: false));
    }

    /** Where the checkout form POSTs to. */
    public function checkoutActionUrl(): string
    {
        return $this->baseUrl().'/pay/checkout';
    }

    /**
     * Every field the PayHere checkout form needs, including the hash.
     *
     * @return array<string, string>
     */
    public function checkoutFields(
        Subscription|LiveStreamAccess $payable,
        ?User $payer,
        string $items,
        string $returnUrl,
        string $cancelUrl
    ): array {
        $amount = $this->formatAmount((float) $payable->amount);
        $currency = $payable->currency;
        $orderId = $payable->payment_order_id;

        // PayHere requires every customer field to be non-empty. We only
        // reliably have name/email; the rest are shown to nobody but the
        // merchant portal, so neutral fallbacks are fine.
        $name = trim($payer?->name ?? '') ?: 'AmaX';
        $firstName = Str::before($name, ' ');
        $lastName = trim(Str::after($name, ' ')) ?: $firstName;
        $country = $payable instanceof Subscription ? ($payable->player?->country ?: 'Sri Lanka') : 'Sri Lanka';

        return [
            'merchant_id' => (string) config('services.payhere.merchant_id'),
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'notify_url' => route('payments.payhere.notify'),
            'order_id' => $orderId,
            'items' => Str::limit($items, 100, ''),
            'currency' => $currency,
            'amount' => $amount,
            'first_name' => Str::limit($firstName, 50, ''),
            'last_name' => Str::limit($lastName, 50, ''),
            'email' => $payer?->email ?: 'no-reply@amasports.jaan.lk',
            'phone' => $payer?->phone ?: '0000000000',
            'address' => 'N/A',
            'city' => 'N/A',
            'country' => $country,
            'hash' => $this->checkoutHash($orderId, $amount, $currency),
        ];
    }

    /**
     * Verify an inbound notify_url POST actually came from PayHere before
     * trusting anything it carries. Fails closed on missing config.
     */
    public function verifyNotification(Request $request): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('PayHere notification received but PAYHERE_MERCHANT_ID/SECRET is not configured — rejecting.');

            return false;
        }

        if ((string) $request->input('merchant_id') !== (string) config('services.payhere.merchant_id')) {
            return false;
        }

        $expected = strtoupper(md5(
            $request->input('merchant_id')
            .$request->input('order_id')
            .$request->input('payhere_amount')
            .$request->input('payhere_currency')
            .$request->input('status_code')
            .$this->hashedSecret()
        ));

        return hash_equals($expected, strtoupper((string) $request->input('md5sig')));
    }

    /** Whether a notification's amount/currency match what we actually asked to be charged. */
    public function amountMatches(Subscription|LiveStreamAccess $payable, $amount, $currency): bool
    {
        return $this->formatAmount((float) $amount) === $this->formatAmount((float) $payable->amount)
            && strtoupper((string) $currency) === strtoupper($payable->currency);
    }

    /**
     * Ask PayHere directly whether an order has been paid — lets return
     * pages confirm instantly instead of waiting on notify_url. Optional:
     * returns null whenever the Retrieval API isn't configured, the order
     * isn't paid (status RECEIVED) yet, or the amount doesn't match.
     *
     * @return array<string, mixed>|null the matching PayHere payment record
     */
    public function findReceivedPayment(Subscription|LiveStreamAccess $payable): ?array
    {
        if (blank(config('services.payhere.app_id')) || blank(config('services.payhere.app_secret')) || ! $payable->payment_order_id) {
            return null;
        }

        try {
            $response = Http::baseUrl($this->baseUrl())
                ->withToken($this->retrievalToken())
                ->acceptJson()
                ->timeout(15)
                ->get('/merchant/v1/payment/search', ['order_id' => $payable->payment_order_id]);

            if ($response->failed() || (int) $response->json('status') !== 1) {
                return null;
            }

            foreach ($response->json('data') ?? [] as $payment) {
                if (($payment['status'] ?? null) === 'RECEIVED'
                    && $this->amountMatches($payable, $payment['amount'] ?? 0, $payment['currency'] ?? '')) {
                    return $payment;
                }
            }
        } catch (Throwable $e) {
            Log::warning('PayHere payment retrieval failed.', [
                'order_id' => $payable->payment_order_id,
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    private function checkoutHash(string $orderId, string $amount, string $currency): string
    {
        return strtoupper(md5(
            config('services.payhere.merchant_id').$orderId.$amount.$currency.$this->hashedSecret()
        ));
    }

    private function hashedSecret(): string
    {
        return strtoupper(md5((string) config('services.payhere.merchant_secret')));
    }

    private function baseUrl(): string
    {
        return config('services.payhere.mode') === 'live'
            ? 'https://www.payhere.lk'
            : 'https://sandbox.payhere.lk';
    }

    /** Client-credentials OAuth token for the Retrieval API, cached just under its lifetime. */
    private function retrievalToken(): string
    {
        return Cache::remember('payhere_access_token_'.config('services.payhere.mode'), now()->addMinutes(9), function () {
            $response = Http::baseUrl($this->baseUrl())
                ->asForm()
                ->withBasicAuth(config('services.payhere.app_id'), config('services.payhere.app_secret'))
                ->timeout(15)
                ->post('/merchant/v1/oauth/token', ['grant_type' => 'client_credentials']);

            if ($response->failed() || blank($response->json('access_token'))) {
                throw new \RuntimeException('Could not authenticate with the PayHere Retrieval API (HTTP '.$response->status().').');
            }

            return $response->json('access_token');
        });
    }
}
