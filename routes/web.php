<?php

use App\Http\Controllers\Payments\SubscriptionPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PayPal redirects the payer's own browser here after hosted checkout —
// plain server-rendered pages, deliberately outside the Sanctum-protected
// /api surface. See SubscriptionPaymentController for why the mobile app
// doesn't rely on this redirect actually happening.
Route::prefix('payments/subscriptions')->name('payments.subscriptions.')->group(function () {
    Route::get('/return', [SubscriptionPaymentController::class, 'return'])->name('return');
    Route::get('/cancel', [SubscriptionPaymentController::class, 'cancel'])->name('cancel');
});
