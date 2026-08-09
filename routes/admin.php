<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LiveScoreController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\MatchPlayerController;
use App\Http\Controllers\Admin\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Registered under the `/admin` prefix + `admin.` name prefix from
| bootstrap/app.php (withRouting -> then). Everything except the login
| screen itself is gated by the `admin` middleware (EnsureAdmin).
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::middleware('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::get('/teams/search', [TeamController::class, 'search'])->name('teams.search');
});
