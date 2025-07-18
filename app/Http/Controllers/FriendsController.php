<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitOAuthService;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FriendsController extends Controller
{
    protected $faceitOAuth;
    protected $faceitService;

    public function __construct(FaceitOAuthService $faceitOAuth, FaceitService $faceitService)
    {
        $this->faceitOAuth = $faceitOAuth;
        $this->faceitService = $faceitService;
    }

    /**
     * Affiche la page des amis (nécessite une authentification)
     */
    public function index()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté pour voir vos amis FACEIT');
        }

        return view('friends');
    }

    /**
     * Récupère la liste des amis avec leurs données de base
     */
    public function getFriends()
    {
        try {
            if (!$this->faceitOAuth->isAuthenticated()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user || !isset($user['player_data']['player_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données joueur manquantes'
                ], 404);
            }

            $playerId = $user['player_data']['player_id'];
            $cacheKey = "friends_list_{$playerId}";

            // Cache pendant 5 minutes pour optimiser
            $friends = Cache::remember($cacheKey, 300, function () use ($playerId) {
                return $this->fetchAndEnrichFriends($playerId);
            });

            return response()->json([
                'success' => true,
                'friends' => $friends,
                'total' => count($friends),
                'cached_at' => Cache::get($cacheKey . '_timestamp', now()->timestamp)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis', [
                'error' => $e->getMessage(),
                'user_id' => $user['id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des amis'
            ], 500);
        }
    }

    /**
     * Récupère et enrichit les données des amis
     */
    private function fetchAndEnrichFriends($playerId)
    {
        // 1. Récupérer le joueur principal pour avoir la liste d'amis
        $player = $this->faceitService->getPlayer($playerId);
        
        if (!$player || !isset($player['friends_ids'])) {
            return [];
        }

        $friendIds = $player['friends_ids'];
        
        if (empty($friendIds)) {
            return [];
        }

        $friends = [];
        $batchSize = 5; // Traiter par lots pour éviter la surcharge

        // Traiter les amis par lots pour optimiser
        $friendBatches = array_chunk($friendIds, $batchSize);
        
        foreach ($friendBatches as $batch) {
            $batchFriends = $this->processFriendsBatch($batch);
            $friends = array_merge($friends, $batchFriends);
            
            // Petite pause entre les lots pour respecter le rate limiting
            if (count($friendBatches) > 1) {
                usleep(200000); // 200ms
            }
        }

        // Trier par ELO décroissant puis par activité
        usort($friends, function($a, $b) {
            $eloA = $a['games']['cs2']['faceit_elo'] ?? $a['games']['csgo']['faceit_elo'] ?? 0;
            $eloB = $b['games']['cs2']['faceit_elo'] ?? $b['games']['csgo']['faceit_elo'] ?? 0;
            
            if ($eloA === $eloB) {
                // Si même ELO, trier par dernière activité
                $timeA = strtotime($a['activated_at'] ?? '2020-01-01');
                $timeB = strtotime($b['activated_at'] ?? '2020-01-01');
                return $timeB - $timeA;
            }
            
            return $eloB - $eloA;
        });

        return $friends;
    }

    /**
     * Traite un lot d'amis
     */
    private function processFriendsBatch($friendIds)
    {
        $friends = [];

        foreach ($friendIds as $friendId) {
            try {
                // Cache individual friend data for 10 minutes
                $cacheKey = "friend_data_{$friendId}";
                
                $friendData = Cache::remember($cacheKey, 600, function () use ($friendId) {
                    return $this->faceitService->getPlayer($friendId);
                });

                if ($friendData && $this->isValidFriend($friendData)) {
                    $friends[] = $this->enrichFriendData($friendData);
                }

            } catch (\Exception $e) {
                Log::warning('Erreur récupération ami', [
                    'friend_id' => $friendId,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return $friends;
    }

    /**
     * Vérifie si un ami a des données valides
     */
    private function isValidFriend($friendData)
    {
        return $friendData && 
               isset($friendData['player_id']) && 
               isset($friendData['nickname']) &&
               (isset($friendData['games']['cs2']) || isset($friendData['games']['csgo']));
    }

    /**
     * Enrichit les données d'un ami avec des infos calculées
     */
    private function enrichFriendData($friendData)
    {
        $csGame = $friendData['games']['cs2'] ?? $friendData['games']['csgo'] ?? null;
        
        $enriched = $friendData;
        $enriched['display_game'] = isset($friendData['games']['cs2']) ? 'cs2' : 'csgo';
        $enriched['faceit_elo'] = $csGame['faceit_elo'] ?? 0;
        $enriched['skill_level'] = $csGame['skill_level'] ?? 1;
        $enriched['last_activity'] = $this->calculateLastActivity($friendData['activated_at'] ?? null);
        $enriched['status'] = $this->getPlayerStatus($enriched['last_activity']);
        $enriched['rank_info'] = $this->getRankInfo($enriched['skill_level']);
        
        return $enriched;
    }

    /**
     * Calcule la dernière activité
     */
    private function calculateLastActivity($activatedAt)
    {
        if (!$activatedAt) {
            return [
                'days_ago' => 999,
                'text' => 'Activité inconnue',
                'timestamp' => null
            ];
        }

        $timestamp = strtotime($activatedAt);
        $now = time();
        $diff = $now - $timestamp;
        $daysAgo = floor($diff / 86400);

        if ($daysAgo === 0) {
            $text = "Aujourd'hui";
        } elseif ($daysAgo === 1) {
            $text = "Hier";
        } elseif ($daysAgo <= 7) {
            $text = "Il y a {$daysAgo} jours";
        } elseif ($daysAgo <= 30) {
            $weeks = floor($daysAgo / 7);
            $text = "Il y a {$weeks} semaine" . ($weeks > 1 ? 's' : '');
        } else {
            $months = floor($daysAgo / 30);
            $text = "Il y a {$months} mois";
        }

        return [
            'days_ago' => $daysAgo,
            'text' => $text,
            'timestamp' => $timestamp
        ];
    }

    /**
     * Détermine le statut du joueur basé sur son activité
     */
    private function getPlayerStatus($lastActivity)
    {
        $daysAgo = $lastActivity['days_ago'];

        if ($daysAgo <= 1) {
            return ['status' => 'online', 'color' => 'green', 'text' => 'Actif'];
        } elseif ($daysAgo <= 7) {
            return ['status' => 'recent', 'color' => 'yellow', 'text' => 'Récent'];
        } elseif ($daysAgo <= 30) {
            return ['status' => 'away', 'color' => 'orange', 'text' => 'Absent'];
        } else {
            return ['status' => 'offline', 'color' => 'gray', 'text' => 'Inactif'];
        }
    }

    /**
     * Informations de rang
     */
    private function getRankInfo($skillLevel)
    {
        $ranks = [
            1 => ['name' => 'Iron', 'color' => 'gray'],
            2 => ['name' => 'Bronze', 'color' => 'yellow'],
            3 => ['name' => 'Silver', 'color' => 'gray'],
            4 => ['name' => 'Gold', 'color' => 'yellow'],
            5 => ['name' => 'Gold+', 'color' => 'yellow'],
            6 => ['name' => 'Platinum', 'color' => 'blue'],
            7 => ['name' => 'Platinum+', 'color' => 'blue'],
            8 => ['name' => 'Diamond', 'color' => 'purple'],
            9 => ['name' => 'Master', 'color' => 'red'],
            10 => ['name' => 'Legendary', 'color' => 'orange']
        ];

        return $ranks[$skillLevel] ?? $ranks[1];
    }

    /**
     * Recherche dans les amis
     */
    public function searchFriends(Request $request)
    {
        try {
            if (!$this->faceitOAuth->isAuthenticated()) {
                return response()->json(['success' => false, 'error' => 'Non authentifié'], 401);
            }

            $query = $request->get('query', '');
            $user = $this->faceitOAuth->getAuthenticatedUser();
            $playerId = $user['player_data']['player_id'];
            
            // Récupérer tous les amis depuis le cache
            $cacheKey = "friends_list_{$playerId}";
            $friends = Cache::get($cacheKey, []);

            if (empty($friends)) {
                // Si pas en cache, les récupérer
                $friends = $this->fetchAndEnrichFriends($playerId);
                Cache::put($cacheKey, $friends, 300);
            }

            // Filtrer selon la recherche
            if (!empty($query)) {
                $friends = array_filter($friends, function($friend) use ($query) {
                    return stripos($friend['nickname'], $query) !== false ||
                           stripos($friend['country'] ?? '', $query) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'friends' => array_values($friends),
                'total' => count($friends),
                'query' => $query
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur recherche amis', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ], 500);
        }
    }

    /**
     * Statistiques des amis
     */
    public function getFriendsStats()
    {
        try {
            if (!$this->faceitOAuth->isAuthenticated()) {
                return response()->json(['success' => false, 'error' => 'Non authentifié'], 401);
            }

            $user = $this->faceitOAuth->getAuthenticatedUser();
            $playerId = $user['player_data']['player_id'];
            
            $cacheKey = "friends_stats_{$playerId}";
            
            $stats = Cache::remember($cacheKey, 600, function () use ($playerId) {
                $friends = $this->fetchAndEnrichFriends($playerId);
                return $this->calculateFriendsStats($friends);
            });

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur stats amis', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du calcul des statistiques'
            ], 500);
        }
    }

    /**
     * Calcule les statistiques des amis
     */
    private function calculateFriendsStats($friends)
    {
        if (empty($friends)) {
            return [
                'total' => 0,
                'online' => 0,
                'average_elo' => 0,
                'highest_elo' => 0,
                'rank_distribution' => [],
                'country_distribution' => []
            ];
        }

        $totalFriends = count($friends);
        $onlineFriends = 0;
        $totalElo = 0;
        $highestElo = 0;
        $rankCounts = array_fill(1, 10, 0);
        $countryCounts = [];

        foreach ($friends as $friend) {
            // Compter les amis actifs
            if ($friend['status']['status'] === 'online') {
                $onlineFriends++;
            }

            // ELO
            $elo = $friend['faceit_elo'];
            $totalElo += $elo;
            $highestElo = max($highestElo, $elo);

            // Distribution des rangs
            $level = $friend['skill_level'];
            if ($level >= 1 && $level <= 10) {
                $rankCounts[$level]++;
            }

            // Distribution des pays
            $country = $friend['country'] ?? 'Unknown';
            $countryCounts[$country] = ($countryCounts[$country] ?? 0) + 1;
        }

        // Trier les pays par nombre d'amis
        arsort($countryCounts);
        $topCountries = array_slice($countryCounts, 0, 5, true);

        return [
            'total' => $totalFriends,
            'online' => $onlineFriends,
            'average_elo' => $totalFriends > 0 ? round($totalElo / $totalFriends) : 0,
            'highest_elo' => $highestElo,
            'rank_distribution' => $rankCounts,
            'country_distribution' => $topCountries
        ];
    }
}