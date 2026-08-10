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

    // Orders API v2 hosted checkout — used for both the $10/year player
    // subscription (mobile, via an in-app browser) and the $5/match admin
    // live-stream unlock (admin panel, plain browser redirect). See
    // App\Services\PayPalService.
    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox|live
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        // From the PayPal Developer Dashboard webhook you register pointing
        // at POST /api/paypal/webhook — required to verify inbound events.
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'currency' => env('PAYPAL_CURRENCY', 'USD'),
    ],

];
