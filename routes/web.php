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
use App\Http\Controllers\ExtensionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['cors'])->group(function () {
    
    // Routes existantes avec support CORS pour l'extension
    Route::get('/api/match/{matchId}/data', [MatchController::class, 'getMatchData'])
        ->name('api.match.data.cors');
    
    Route::get('/api/player/{playerId}/stats', [PlayerController::class, 'getPlayerStats'])
        ->name('api.player.stats.cors');
        
    Route::post('/api/compare', [ComparisonController::class, 'compare'])
        ->name('api.compare.cors');
});

Route::prefix('api/extension')->name('api.extension.')->group(function () {
    
    // Vérification de version pour les mises à jour automatiques
    Route::get('/version', [ExtensionController::class, 'getVersion'])->name('version');
    
    // Status de l'API pour vérifier la disponibilité
    Route::get('/status', [ExtensionController::class, 'getStatus'])->name('status');
    
    // Analytics pour l'extension (optionnel)
    Route::post('/analytics', [ExtensionController::class, 'recordAnalytics'])->name('analytics');
    
    // Feedback des utilisateurs de l'extension
    Route::post('/feedback', [ExtensionController::class, 'submitFeedback'])->name('feedback');
});

// Routes publiques
Route::get('/api/match/{matchId}/data', [MatchController::class, 'getMatchData'])->name('api.match.data');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advanced', [PlayerController::class, 'advanced'])->name('advanced');
Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');
Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards');
Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments');
Route::get('/match', [MatchController::class, 'index'])->name('match');

// Routes protégées (nécessitent une authentification FACEIT)
Route::middleware(['faceit.auth'])->group(function () {
    // Page des amis
    Route::get('/friends', [FriendsController::class, 'index'])->name('friends');
    
    // Routes de profil PRINCIPALES
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/sync-faceit', [ProfileController::class, 'syncFaceitData'])->name('profile.sync');
    Route::get('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');
    
    // API profil
    Route::get('/api/profile/data', [ProfileController::class, 'getProfileData'])->name('api.profile.data');
});

// Routes d'authentification FACEIT
Route::prefix('auth/faceit')->name('auth.faceit.')->group(function () {
    // Redirection vers FACEIT
    Route::get('/login', [FaceitAuthController::class, 'redirectToFaceit'])->name('login');
    
    // Callback après authentification FACEIT
    Route::get('/callback', [FaceitAuthController::class, 'handleFaceitCallback'])->name('callback');
    
    // Déconnexion
    Route::post('/logout', [FaceitAuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [FaceitAuthController::class, 'logout'])->name('logout.get');
    
    // Popup de connexion (pour JavaScript)
    Route::get('/popup', [FaceitAuthController::class, 'loginPopup'])->name('popup');
    Route::get('/popup/callback', [FaceitAuthController::class, 'popupCallback'])->name('popup.callback');
});

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
    
    // Routes pour les classements
    Route::get('/leaderboard', [LeaderboardController::class, 'getLeaderboard'])->name('api.leaderboard');
    Route::get('/leaderboard/search-player', [LeaderboardController::class, 'searchPlayer'])->name('api.leaderboard.search');
    Route::get('/leaderboard/top-players', [LeaderboardController::class, 'getTopPlayers'])->name('api.leaderboard.top');
    Route::get('/leaderboard/region-stats', [LeaderboardController::class, 'getRegionStats'])->name('api.leaderboard.stats');
    
    // Routes pour les championnats FACEIT
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

    // API Amis
    Route::get('/friends', [FriendsController::class, 'getFriends']);
    Route::get('/friends/search', [FriendsController::class, 'searchFriends']);
    Route::get('/friends/stats', [FriendsController::class, 'getFriendsStats']);
});

// API pour vérifier l'authentification
Route::prefix('api/auth')->name('api.auth.')->group(function () {
    Route::get('/user', [FaceitAuthController::class, 'getCurrentUser'])->name('user');
    Route::get('/status', [FaceitAuthController::class, 'checkAuthStatus'])->name('status');
});

// Routes de test (à supprimer en production)
Route::prefix('test')->group(function () {
    Route::get('/auth', function () {
        return view('test.auth');
    })->name('test.auth');
    
    Route::get('/popup', function () {
        return view('test.popup');
    })->name('test.popup');
});