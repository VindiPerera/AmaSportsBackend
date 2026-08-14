<?php

use App\Http\Controllers\PublicController;
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

use App\Http\Controllers\UserMatchController;
use App\Http\Controllers\UserWebAuthController;

// ─── Public Website ──────────────────────────────────────────────────────────
Route::get('/', fn () => redirect('/home'))->name('public.root');
Route::get('/home',    [PublicController::class, 'home'])->name('public.home');
Route::get('/about',   [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'contactStore'])->name('public.contact.store');
Route::get('/matches', [PublicController::class, 'matches'])->name('public.matches');

// ─── User Web Authentication ──────────────────────────────────────────────────
Route::get('/login', [UserWebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [UserWebAuthController::class, 'login'])->name('login.store');
Route::get('/register', [UserWebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [UserWebAuthController::class, 'register'])->name('register.store');
Route::post('/logout', [UserWebAuthController::class, 'logout'])->name('user.logout');

// ─── Mobile App Web Access ────────────────────────────────────────────────────
//
// /app *is* the mobile app itself (no wrapper page, no iframe/phone-frame
// preview — that was tried and dropped). Root `/` belongs to the public
// site above, so the mobile app can't live there; Expo Router resolves the
// current screen from the *browser* URL, so this export was built with
// expo.experiments.baseUrl = "/app" (sport-mobile/app.json, set only for
// this build then reverted — the root-relative export public/index.html/
// _expo/assets/ used by the fallback below still deploy:web's normally).
// Without that base path, the router doesn't recognize "/app" as one of
// its own routes and shows its built-in "Unmatched Route" screen instead.
//
// {any?} is required so hard-refreshing/deep-linking into a route the RN
// app navigated to client-side (e.g. /app/login) still resolves to the SPA
// shell instead of 404ing — real static files under /app (_expo/assets/
// favicon.ico) are served directly by the webserver before this ever runs.
Route::get('/app/{any?}', function () {
    $shell = public_path('app/index.html');
    abort_unless(is_file($shell), 404);
    return Response::file($shell);
})->where('any', '.*')->name('public.app');

Route::get('/mobile', fn () => redirect()->route('public.app'))->name('public.mobile');

// ─── Authenticated User Web Application (Matches & Schedule / Match Creation) ─
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserMatchController::class, 'index'])->name('dashboard');
    Route::get('/user/matches', [UserMatchController::class, 'index'])->name('user.matches.index');
    Route::get('/user/matches/create', [UserMatchController::class, 'create'])->name('user.matches.create');
    Route::post('/user/matches', [UserMatchController::class, 'store'])->name('user.matches.store');
    Route::get('/user/matches/{match}/edit', [UserMatchController::class, 'edit'])->name('user.matches.edit');
    Route::put('/user/matches/{match}', [UserMatchController::class, 'update'])->name('user.matches.update');
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

    $shell = is_file(public_path('spa.html')) ? public_path('spa.html') : public_path('index.html');

    abort_unless(is_file($shell), 404);

    return Response::file($shell);
});
