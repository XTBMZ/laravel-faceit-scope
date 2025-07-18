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
Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards');
Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments');
Route::get('/match', [MatchController::class, 'index'])->name('match');

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

// Routes de profil et amis (nécessitent une authentification)
Route::middleware('faceit.auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/sync-faceit', [ProfileController::class, 'syncFaceitData'])->name('profile.sync');
    Route::get('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');
    Route::get('/profile/match-history', [ProfileController::class, 'getMatchHistory'])->name('profile.history');
    
    Route::get('/friends', [FriendsController::class, 'index'])->name('friends');
    
    // API pour récupérer les amis
    Route::get('/api/friends', [FriendsController::class, 'getFriends'])->name('api.friends');
    
    // API pour récupérer les amis en ligne
    Route::get('/api/friends/online', [FriendsController::class, 'getOnlineFriends'])->name('api.friends.online');
    
    // API pour rechercher dans les amis
    Route::get('/api/friends/search', [FriendsController::class, 'searchFriends'])->name('api.friends.search');
    
    // API pour comparer avec un ami
    Route::get('/api/friends/{friendId}/compare', [FriendsController::class, 'compareWithFriend'])->name('api.friends.compare');

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