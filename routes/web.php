<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ComparisonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advanced', [PlayerController::class, 'advanced'])->name('advanced');
Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');

// Routes API pour les données FACEIT
Route::prefix('api')->group(function () {
    Route::get('/player/search/{nickname}', [PlayerController::class, 'searchByNickname'])->name('api.player.search');
    Route::get('/player/{playerId}', [PlayerController::class, 'getPlayer'])->name('api.player.get');
    Route::get('/player/{playerId}/stats', [PlayerController::class, 'getPlayerStats'])->name('api.player.stats');
    Route::get('/match/{matchId}', [PlayerController::class, 'getMatch'])->name('api.match.get');
    
    // Routes pour la comparaison
    Route::post('/compare', [ComparisonController::class, 'compare'])->name('api.compare');
});