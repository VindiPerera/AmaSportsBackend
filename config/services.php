<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // PayHere Checkout API (hosted checkout, https://support.payhere.lk/api-&-mobile-sdk/checkout-api)
    // — used for both the player subscription (mobile, via an in-app
    // browser) and the per-match live-stream unlock (mobile VIP + admin
    // panel). See App\Services\PayHereService.
    'payhere' => [
        'mode' => env('PAYHERE_MODE', 'sandbox'), // sandbox|live
        'merchant_id' => env('PAYHERE_MERCHANT_ID'),
        // Settings > Domains & Credentials in the PayHere merchant portal —
        // generated per domain/app, so the secret must be the one issued
        // for the domain APP_URL points at.
        'merchant_secret' => env('PAYHERE_MERCHANT_SECRET'),
        // LKR, USD, GBP, EUR or AUD — non-LKR currencies must be enabled on
        // the merchant account. Amounts in the DB/admin panel are charged
        // in this currency as-is (no conversion).
        'currency' => env('PAYHERE_CURRENCY', 'USD'),
        // Optional: Business App credentials (Settings > API Keys, with the
        // "Payment Retrieval API" permission). When set, return pages can
        // confirm a payment immediately instead of waiting for notify_url.
        'app_id' => env('PAYHERE_APP_ID'),
        'app_secret' => env('PAYHERE_APP_SECRET'),
    ],

];
