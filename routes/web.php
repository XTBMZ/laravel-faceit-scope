<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\LeaderboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advanced', [PlayerController::class, 'advanced'])->name('advanced');
Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');
Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards');

// Routes API pour les données FACEIT
Route::prefix('api')->group(function () {
    Route::get('/player/search/{nickname}', [PlayerController::class, 'searchByNickname'])->name('api.player.search');
    Route::get('/player/{playerId}', [PlayerController::class, 'getPlayer'])->name('api.player.get');
    Route::get('/player/{playerId}/stats', [PlayerController::class, 'getPlayerStats'])->name('api.player.stats');
    Route::get('/match/{matchId}', [PlayerController::class, 'getMatch'])->name('api.match.get');
    
    // Routes pour la comparaison
    Route::post('/compare', [ComparisonController::class, 'compare'])->name('api.compare');
    
    // Routes pour les classements
    Route::get('/leaderboard', [LeaderboardController::class, 'getLeaderboard'])->name('api.leaderboard');
    Route::get('/leaderboard/search-player', [LeaderboardController::class, 'searchPlayer'])->name('api.leaderboard.search');
    Route::get('/leaderboard/top-players', [LeaderboardController::class, 'getTopPlayers'])->name('api.leaderboard.top');
    Route::get('/leaderboard/region-stats', [LeaderboardController::class, 'getRegionStats'])->name('api.leaderboard.stats');
});