<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\MatchController; // Nouveau contrôleur

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advanced', [PlayerController::class, 'advanced'])->name('advanced');
Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');
Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards');
Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments');

// Nouvelle route pour l'analyse de match
Route::get('/match', [MatchController::class, 'index'])->name('match');

// Routes API pour les données FACEIT
Route::prefix('api')->group(function () {
    // Routes joueurs
    Route::get('/player/search/{nickname}', [PlayerController::class, 'searchByNickname'])->name('api.player.search');
    Route::get('/player/{playerId}', [PlayerController::class, 'getPlayer'])->name('api.player.get');
    Route::get('/player/{playerId}/stats', [PlayerController::class, 'getPlayerStats'])->name('api.player.stats');
    Route::get('/match/{matchId}', [PlayerController::class, 'getMatch'])->name('api.match.get');
    
    // Nouvelles routes pour l'analyse de match
    Route::get('/match/{matchId}', [PlayerController::class, 'getMatch']);
    Route::get('/match/{matchId}/data', [MatchController::class, 'getMatchData']);
    Route::get('/match/{matchId}/stats', [MatchController::class, 'getMatchStats']);
    
    // Routes pour la comparaison
    Route::post('/compare', [ComparisonController::class, 'compare'])->name('api.compare');
    
    // Routes pour les classements
    Route::get('/leaderboard', [LeaderboardController::class, 'getLeaderboard'])->name('api.leaderboard');
    Route::get('/leaderboard/search-player', [LeaderboardController::class, 'searchPlayer'])->name('api.leaderboard.search');
    Route::get('/leaderboard/top-players', [LeaderboardController::class, 'getTopPlayers'])->name('api.leaderboard.top');
    Route::get('/leaderboard/region-stats', [LeaderboardController::class, 'getRegionStats'])->name('api.leaderboard.stats');
    
    // Routes pour les championnats FACEIT
    Route::prefix('tournaments')->group(function () {
        // Page principale des tournois
        Route::get('/', [TournamentController::class, 'index'])->name('tournaments.index');
        
        // API Routes basées sur l'API FACEIT officielle
        Route::prefix('api')->group(function () {
            // Récupérer tous les championnats d'un jeu
            Route::get('/championships', [TournamentController::class, 'getChampionships'])->name('api.championships.list');
            
            // Récupérer les détails d'un championnat
            Route::get('/championships/{id}', [TournamentController::class, 'getChampionshipDetails'])->name('api.championships.details');
            
            // Récupérer tous les matches d'un championnat
            Route::get('/championships/{id}/matches', [TournamentController::class, 'getChampionshipMatches'])->name('api.championships.matches');
            
            // Récupérer tous les résultats d'un championnat
            Route::get('/championships/{id}/results', [TournamentController::class, 'getChampionshipResults'])->name('api.championships.results');
            
            // Récupérer toutes les inscriptions d'un championnat
            Route::get('/championships/{id}/subscriptions', [TournamentController::class, 'getChampionshipSubscriptions'])->name('api.championships.subscriptions');
            
            // Recherche de championnats
            Route::get('/championships/search', [TournamentController::class, 'searchChampionships'])->name('api.championships.search');
            
            // Statistiques globales
            Route::get('/stats', [TournamentController::class, 'getGlobalStats'])->name('api.championships.stats');
        });
    });
});