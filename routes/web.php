<?php

use App\Http\Controllers\Payments\StreamAccessPaymentController;
use App\Http\Controllers\Payments\SubscriptionPaymentController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// PayPal redirects the payer's own browser here after hosted checkout —
// plain server-rendered pages, deliberately outside the Sanctum-protected
// /api surface. See SubscriptionPaymentController for why the mobile app
// doesn't rely on this redirect actually happening.
Route::prefix('payments/subscriptions')->name('payments.subscriptions.')->group(function () {
    Route::get('/return', [SubscriptionPaymentController::class, 'return'])->name('return');
    Route::get('/cancel', [SubscriptionPaymentController::class, 'cancel'])->name('cancel');
});

// Same, for a player's own $5 "VIP" live-stream unlock purchase (see
// Api\StreamAccessController) — not to be confused with the admin-only
// equivalent under /admin/matches/{match}/stream/{return,cancel}, which
// stays gated behind the `admin` middleware since it's hit from an admin's
// authenticated browser session, not a player's.
Route::prefix('payments/stream-access')->name('payments.stream-access.')->group(function () {
    Route::get('/return', [StreamAccessPaymentController::class, 'return'])->name('return');
    Route::get('/cancel', [StreamAccessPaymentController::class, 'cancel'])->name('cancel');
});

// Mobile app (SPA shell).
//
// `npm run deploy:web` copies the Expo Router web export
// (sport-mobile/dist) into public/, giving us public/index.html plus its
// _expo/ and assets/ folders. This catch-all serves that shell for any
// GET request nothing else claimed, so Expo Router's client-side
// navigation can take over for app routes like /player-profile/5.
//
// It's registered last and only fires when no other route matched, so
// /admin/* and /api/* (and the payments routes above) are never shadowed
// by it. /api/* is explicitly re-excluded below anyway, so an unknown API
// endpoint still gets the JSON 404 from bootstrap/app.php's exception
// handler instead of the HTML shell. Real static files (JS/CSS/images
// that exist on disk) never reach this at all — public/.htaccess sends
// those straight to the webserver before Laravel is even booted.
Route::fallback(function () {
    abort_if(request()->is('api/*', 'admin/*'), 404);

    $shell = public_path('index.html');

    abort_unless(is_file($shell), 404);

    return Response::file($shell);
});
