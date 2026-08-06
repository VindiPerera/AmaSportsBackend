<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\CricketProfileController;
use App\Http\Controllers\Api\HockeyProfileController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PlayerProfileController;
use App\Http\Controllers\Api\PlayerSportController;
use App\Http\Controllers\Api\ProfileController;
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
// the score-update endpoint is authenticated + admin-gated.
Route::get('/matches', [MatchController::class, 'index']);
Route::get('/matches/{match}', [MatchController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ProfileController::class, 'show']);
    Route::patch('/user', [ProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'changePassword']);

    Route::get('/player/sports', [PlayerSportController::class, 'index']);
    Route::post('/player/sports', [PlayerSportController::class, 'store']);

    Route::get('/player/profile', [PlayerProfileController::class, 'show']);
    Route::post('/player/profile', [PlayerProfileController::class, 'update']);

    Route::get('/player/cricket-profile', [CricketProfileController::class, 'show']);
    Route::put('/player/cricket-profile', [CricketProfileController::class, 'update']);

    Route::get('/player/hockey-profile', [HockeyProfileController::class, 'show']);
    Route::put('/player/hockey-profile', [HockeyProfileController::class, 'update']);

    Route::patch('/matches/{match}/score', [MatchController::class, 'updateScore']);

    Route::post('/contact', [ContactMessageController::class, 'store']);
});
