<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LiveScoreController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\MatchPlayerController;
use App\Http\Controllers\Admin\PlayerAnalysisController;
use App\Http\Controllers\Admin\StreamAccessController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Registered under the `/admin` prefix + `admin.` name prefix from
| bootstrap/app.php (withRouting -> then).
|
| Three tiers:
|   - Public (no middleware beyond `web`): login form, the dashboard
|     (now a public player-search/analysis home page — anyone can view
|     it), and individual player analysis pages.
|   - `admin` (EnsureAdmin): match creation/scoring/roster/streaming —
|     unchanged from before.
|   - `super_admin` (EnsureSuperAdmin): payments/users/clients overview
|     — a stricter tier regular admins cannot reach.
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// Public home — player search + analysis, visible to anyone, no login.
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/players/{player}', [PlayerAnalysisController::class, 'show'])->name('players.show');

Route::middleware('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/create', [MatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
    Route::get('/matches/{match}/edit', [MatchController::class, 'edit'])->name('matches.edit');
    Route::put('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');

    Route::get('/matches/{match}/players', [MatchPlayerController::class, 'index'])->name('matches.players.index');
    Route::post('/matches/{match}/players', [MatchPlayerController::class, 'store'])->name('matches.players.store');
    Route::put('/matches/{match}/players/{matchPlayer}', [MatchPlayerController::class, 'update'])->name('matches.players.update');
    Route::delete('/matches/{match}/players/{matchPlayer}', [MatchPlayerController::class, 'destroy'])->name('matches.players.destroy');

    Route::get('/matches/{match}/live', [LiveScoreController::class, 'show'])->name('live-score.show');
    Route::post('/matches/{match}/live/start', [LiveScoreController::class, 'start'])->name('live-score.start');
    Route::post('/matches/{match}/live/update', [LiveScoreController::class, 'update'])->name('live-score.update');
    Route::post('/matches/{match}/live/finish', [LiveScoreController::class, 'finish'])->name('live-score.finish');

    // $5/match live-stream unlock (Phase 6 revision 2) — Live Score stays
    // free; this only gates whether the match's YouTube embed is exposed.
    Route::get('/matches/{match}/stream', [StreamAccessController::class, 'show'])->name('matches.stream.show');
    Route::post('/matches/{match}/stream/create-order', [StreamAccessController::class, 'createOrder'])->name('matches.stream.create-order');
    Route::get('/matches/{match}/stream/return', [StreamAccessController::class, 'return'])->name('matches.stream.return');
    Route::get('/matches/{match}/stream/cancel', [StreamAccessController::class, 'cancel'])->name('matches.stream.cancel');
    Route::put('/matches/{match}/stream/url', [StreamAccessController::class, 'updateUrl'])->name('matches.stream.update-url');

    Route::get('/teams/search', [TeamController::class, 'search'])->name('teams.search');

    // Admin Users, Payments & Purchases management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users.index');
    Route::get('/payments', [SuperAdminController::class, 'payments'])->name('payments.index');
    Route::get('/purchases', [SuperAdminController::class, 'purchases'])->name('purchases.index');
});

Route::middleware('super_admin')->prefix('super')->name('super.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/payments', [SuperAdminController::class, 'payments'])->name('payments');
});

