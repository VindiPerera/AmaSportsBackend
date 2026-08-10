<?php

use App\Http\Controllers\Api\AthleticsProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BaseBallProfileController;
use App\Http\Controllers\Api\BasketballProfileController;
use App\Http\Controllers\Api\BeachVolleyballProfileController;
use App\Http\Controllers\Api\BoxingProfileController;
use App\Http\Controllers\Api\ChessProfileController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\CricketAnalysisController;
use App\Http\Controllers\Api\CricketProfileController;
use App\Http\Controllers\Api\ElleProfileController;
use App\Http\Controllers\Api\FootballProfileController;
use App\Http\Controllers\Api\HockeyProfileController;
use App\Http\Controllers\Api\JudoProfileController;
use App\Http\Controllers\Api\KabadiProfileController;
use App\Http\Controllers\Api\KarateProfileController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\NetBallProfileController;
use App\Http\Controllers\Api\PayPalWebhookController;
use App\Http\Controllers\Api\PlayerProfileController;
use App\Http\Controllers\Api\PlayerSportController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RacketSportProfileController;
use App\Http\Controllers\Api\RugbyProfileController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SwimmingProfileController;
use App\Http\Controllers\Api\VolleyballProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are automatically prefixed with /api. Public auth routes
| live under /auth/*; everything else requires a valid Sanctum token.
|
| When adding new modules (teams, performance, live scores, notifications,
| etc.), give each its own controller + route group below rather than
| growing AuthController/ProfileController.
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

// Lookup tables (sports, formats, age categories, match categories, cricket
// match types) — public, read-only, editable later from an admin panel.
Route::get('/lookups', [LookupController::class, 'index']);

// Live Score — list/detail are public so the tab works before/without auth;
// the score-update endpoint is authenticated + admin-gated. Free on every
// platform, unaffected by the subscription/live-stream paywalls below.
Route::get('/matches', [MatchController::class, 'index']);
Route::get('/matches/{match}', [MatchController::class, 'show']);

// PayPal's server-to-server callback — deliberately outside auth:sanctum
// (PayPal isn't a logged-in player) but signature-verified inside the
// controller itself before anything is trusted (PayPalService::verifyWebhookSignature).
Route::post('/paypal/webhook', [PayPalWebhookController::class, 'handle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ProfileController::class, 'show']);
    Route::patch('/user', [ProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'changePassword']);

    // $10/year app subscription (Phase 6 revision 2) — gates "Add Sport"
    // and Analysis (and, per product decision, editing sport profiles the
    // player already registered) via the `subscription.active` middleware
    // applied to each PUT/analysis route below.
    Route::post('/subscriptions/create-order', [SubscriptionController::class, 'createOrder']);
    Route::get('/player/subscription-status', [SubscriptionController::class, 'status']);

    // No POST here — a player_sports row is only ever created inside a
    // sport profile's own submit endpoint (see PlayerSportController).
    Route::get('/player/sports', [PlayerSportController::class, 'index']);

    // Shared header (name/country/photos) — account info, not sport data,
    // so it stays free regardless of subscription status.
    Route::get('/player/profile', [PlayerProfileController::class, 'show']);
    Route::post('/player/profile', [PlayerProfileController::class, 'update']);

    Route::get('/player/cricket-profile', [CricketProfileController::class, 'show']);
    Route::put('/player/cricket-profile', [CricketProfileController::class, 'update'])->middleware('subscription.active');
    // Read-only aggregated stats for the Analysis tab (spec Phase 5) — never
    // writes anything, so it lives alongside the profile routes but has no
    // PUT/POST counterpart. Analysis requires an active subscription.
    Route::get('/player/cricket-analysis', CricketAnalysisController::class)->middleware('subscription.active');

    Route::get('/player/hockey-profile', [HockeyProfileController::class, 'show']);
    Route::put('/player/hockey-profile', [HockeyProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/base-ball-profile', [BaseBallProfileController::class, 'show']);
    Route::put('/player/base-ball-profile', [BaseBallProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/net-ball-profile', [NetBallProfileController::class, 'show']);
    Route::put('/player/net-ball-profile', [NetBallProfileController::class, 'update'])->middleware('subscription.active');

    // Shared Tennis/Badminton/Table Tennis form — disambiguated by a
    // `sport_id` query/body param (see RacketSportProfileController).
    Route::get('/player/racket-sport-profile', [RacketSportProfileController::class, 'show']);
    Route::put('/player/racket-sport-profile', [RacketSportProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/kabadi-profile', [KabadiProfileController::class, 'show']);
    Route::put('/player/kabadi-profile', [KabadiProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/judo-profile', [JudoProfileController::class, 'show']);
    Route::put('/player/judo-profile', [JudoProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/basketball-profile', [BasketballProfileController::class, 'show']);
    Route::put('/player/basketball-profile', [BasketballProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/football-profile', [FootballProfileController::class, 'show']);
    Route::put('/player/football-profile', [FootballProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/rugby-profile', [RugbyProfileController::class, 'show']);
    Route::put('/player/rugby-profile', [RugbyProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/boxing-profile', [BoxingProfileController::class, 'show']);
    Route::put('/player/boxing-profile', [BoxingProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/karate-profile', [KarateProfileController::class, 'show']);
    Route::put('/player/karate-profile', [KarateProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/chess-profile', [ChessProfileController::class, 'show']);
    Route::put('/player/chess-profile', [ChessProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/athletics-profile', [AthleticsProfileController::class, 'show']);
    Route::put('/player/athletics-profile', [AthleticsProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/swimming-profile', [SwimmingProfileController::class, 'show']);
    Route::put('/player/swimming-profile', [SwimmingProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/volleyball-profile', [VolleyballProfileController::class, 'show']);
    Route::put('/player/volleyball-profile', [VolleyballProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/beach-volleyball-profile', [BeachVolleyballProfileController::class, 'show']);
    Route::put('/player/beach-volleyball-profile', [BeachVolleyballProfileController::class, 'update'])->middleware('subscription.active');

    Route::get('/player/elle-profile', [ElleProfileController::class, 'show']);
    Route::put('/player/elle-profile', [ElleProfileController::class, 'update'])->middleware('subscription.active');

    Route::patch('/matches/{match}/score', [MatchController::class, 'updateScore']);

    Route::post('/contact', [ContactMessageController::class, 'store']);
});
