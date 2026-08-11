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

];
