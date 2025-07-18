<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\Auth\FaceitAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advanced', [PlayerController::class, 'advanced'])->name('advanced');
Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');

// ✅ GARDER - Route pour afficher la page (mais plus d'API)
Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards');

Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments');
Route::get('/match', [MatchController::class, 'index'])->name('match');

// Routes API pour les données FACEIT
Route::prefix('api')->group(function () {
    // Routes joueurs
    Route::get('/player/search/{nickname}', [PlayerController::class, 'searchByNickname'])->name('api.player.search');
    Route::get('/player/{playerId}', [PlayerController::class, 'getPlayer'])->name('api.player.get');
    Route::get('/player/{playerId}/stats', [PlayerController::class, 'getPlayerStats'])->name('api.player.stats');
    
    // Routes pour l'analyse de match
    Route::get('/match/{matchId}', [PlayerController::class, 'getMatch'])->name('api.match.get');
    Route::get('/match/{matchId}/data', [MatchController::class, 'getMatchData'])->name('api.match.data');
    Route::get('/match/{matchId}/stats', [MatchController::class, 'getMatchStats'])->name('api.match.stats');
    
    // Routes pour la comparaison
    Route::post('/compare', [ComparisonController::class, 'compare'])->name('api.compare');
    
    // Routes pour les championnats FACEIT (garder)
    Route::prefix('tournaments')->group(function () {
        Route::prefix('api')->group(function () {
            Route::get('/championships', [TournamentController::class, 'getChampionships'])->name('api.championships.list');
            Route::get('/championships/{id}', [TournamentController::class, 'getChampionshipDetails'])->name('api.championships.details');
            Route::get('/championships/{id}/matches', [TournamentController::class, 'getChampionshipMatches'])->name('api.championships.matches');
            Route::get('/championships/{id}/results', [TournamentController::class, 'getChampionshipResults'])->name('api.championships.results');
            Route::get('/championships/{id}/subscriptions', [TournamentController::class, 'getChampionshipSubscriptions'])->name('api.championships.subscriptions');
            Route::get('/championships/search', [TournamentController::class, 'searchChampionships'])->name('api.championships.search');
            Route::get('/stats', [TournamentController::class, 'getGlobalStats'])->name('api.championships.stats');
        });
    });

    // Routes pour les amis (garder)
    Route::get('/friends', [FriendsController::class, 'getFriends']);
    Route::get('/friends/search', [FriendsController::class, 'searchFriends']);
    Route::get('/friends/stats', [FriendsController::class, 'getFriendsStats']);
});

// API pour vérifier l'authentification (garder)
Route::prefix('api/auth')->name('api.auth.')->group(function () {
    Route::get('/user', [FaceitAuthController::class, 'getCurrentUser'])->name('user');
    Route::get('/status', [FaceitAuthController::class, 'checkAuthStatus'])->name('status');
});
