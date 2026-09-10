<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Gate Bypass (development only)
    |--------------------------------------------------------------------------
    |
    | When true, every "requires an active AmaSports subscription" check
    | (EnsureActiveSubscription middleware + the /player/subscription-status
    | endpoint the mobile app reads to decide whether to show the paywall)
    | is treated as satisfied, regardless of any real Subscription row.
    |
    | Purely a development convenience so sport profiles / Analysis can be
    | exercised locally without going through PayPal checkout. Nothing about
    | the real gating logic is removed — set SUBSCRIPTION_BYPASS=false (or
    | unset it) to restore normal enforcement.
    |
    | Guarded so it can never accidentally take effect outside local/testing
    | even if the env var is left set by mistake. Uses the raw env() helper
    | rather than app()->environment() deliberately — this file is required
    | during the LoadConfiguration bootstrap step, before the container's
    | 'env' binding exists, so app()->environment() throws here.
    |
    */

    'bypass' => env('SUBSCRIPTION_BYPASS', false) && env('APP_ENV', 'production') !== 'production',

    /*
    |--------------------------------------------------------------------------
    | Mobile Deep Link Return URL
    |--------------------------------------------------------------------------
    |
    | Where resources/views/payments/result.blade.php bounces the browser
    | after rendering (subscription and stream-access PayPal flows both
    | share that view). Must match the `scheme` in Frontend/app.json so
    | expo-web-browser's openAuthSessionAsync() can detect the redirect and
    | auto-close the in-app browser sheet. This carries no payment data —
    | capture already happened server-side before this redirect fires.
    |
    */

    'mobile_return_scheme' => env('SUBSCRIPTION_MOBILE_RETURN_URL', 'amasports://payment-return'),

];
