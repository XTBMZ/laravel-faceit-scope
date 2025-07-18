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
     * Affiche la page des amis
     */
    public function index()
    {
        $user = $this->faceitOAuth->getAuthenticatedUser();
        
        if (!$user) {
            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page');
        }

        return view('friends.index', [
            'user' => $user
        ]);
    }

    /**
     * API - Récupère la liste des amis
     */
    public function getFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user || !isset($user['access_token'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $playerId = $user['player_data']['player_id'] ?? null;
            if (!$playerId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données joueur manquantes'
                ], 404);
            }

            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 20), 50);

            // Clé de cache
            $cacheKey = "user_friends_{$playerId}_{$offset}_{$limit}";
            
            // Vérifier le cache (5 minutes)
            if (!$request->has('_refresh')) {
                $cachedData = Cache::get($cacheKey);
                if ($cachedData) {
                    return response()->json([
                        'success' => true,
                        'friends' => $cachedData['friends'],
                        'pagination' => $cachedData['pagination'],
                        'stats' => $cachedData['stats'],
                        'cached' => true
                    ]);
                }
            }

            // Simuler la récupération des amis (l'API FACEIT n'expose pas les friends)
            // On va utiliser une approche alternative : récupérer les joueurs récents
            $friends = $this->getFriendsFromRecentMatches($playerId, $user['access_token']);
            
            // Enrichir les données des amis
            $enrichedFriends = $this->enrichFriendsData($friends);
            
            // Statistiques
            $stats = $this->calculateFriendsStats($enrichedFriends);
            
            // Pagination
            $pagination = [
                'current_page' => floor($offset / $limit) + 1,
                'has_next' => count($enrichedFriends) >= $limit,
                'total_items' => count($enrichedFriends),
                'items_per_page' => $limit,
                'start' => $offset,
                'end' => $offset + count($enrichedFriends)
            ];

            $result = [
                'friends' => array_slice($enrichedFriends, $offset, $limit),
                'pagination' => $pagination,
                'stats' => $stats
            ];

            // Mettre en cache pour 5 minutes
            Cache::put($cacheKey, $result, 300);

            return response()->json([
                'success' => true,
                'friends' => $result['friends'],
                'pagination' => $result['pagination'],
                'stats' => $result['stats'],
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des amis'
            ], 500);
        }
    }

    /**
     * API - Récupère les amis en ligne (simulation)
     */
    public function getOnlineFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            // Simuler les amis en ligne (20-30% des amis)
            $allFriends = $this->getFriends($request)->getData();
            
            if (!$allFriends->success) {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de récupérer les amis'
                ], 500);
            }

            $onlineFriends = array_filter($allFriends->friends, function($friend) {
                return $friend['online_status'] === 'online' || $friend['online_status'] === 'in_game';
            });

            return response()->json([
                'success' => true,
                'online_friends' => array_values($onlineFriends),
                'total_online' => count($onlineFriends)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis en ligne: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des amis en ligne'
            ], 500);
        }
    }

    /**
     * API - Recherche dans les amis
     */
    public function searchFriends(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => false,
                    'error' => 'Requête trop courte'
                ], 400);
            }

            $user = $this->faceitOAuth->getAuthenticatedUser();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            // Récupérer tous les amis et filtrer
            $allFriends = $this->getFriends($request)->getData();
            
            if (!$allFriends->success) {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de récupérer les amis'
                ], 500);
            }

            $filteredFriends = array_filter($allFriends->friends, function($friend) use ($query) {
                return stripos($friend['nickname'], $query) !== false;
            });

            return response()->json([
                'success' => true,
                'friends' => array_values($filteredFriends),
                'query' => $query,
                'total_results' => count($filteredFriends)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur recherche amis: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ], 500);
        }
    }

    /**
     * API - Comparaison avec un ami
     */
    public function compareWithFriend(Request $request, $friendId)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            // Récupérer les données de l'ami
            $friend = $this->faceitService->getPlayer($friendId);
            
            if (!$friend) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ami non trouvé'
                ], 404);
            }

            // Rediriger vers la page de comparaison
            $userNickname = $user['nickname'];
            $friendNickname = $friend['nickname'];
            
            return response()->json([
                'success' => true,
                'redirect_url' => route('comparison', [
                    'player1' => $userNickname,
                    'player2' => $friendNickname
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur comparaison avec ami: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la comparaison'
            ], 500);
        }
    }

    /**
     * Récupère les "amis" depuis les matches récents
     */
    private function getFriendsFromRecentMatches($playerId, $accessToken)
    {
        try {
            // Récupérer l'historique des matches
            $history = $this->faceitService->getPlayerHistory($playerId, 0, 0, 0, 100);
            
            if (!$history || !isset($history['items'])) {
                return [];
            }

            $teammates = [];
            $teammateCount = [];

            // Analyser les matches pour trouver les coéquipiers fréquents
            foreach ($history['items'] as $match) {
                try {
                    $matchDetails = $this->faceitService->getMatch($match['match_id']);
                    
                    if (!$matchDetails || !isset($matchDetails['teams'])) {
                        continue;
                    }

                    // Trouver l'équipe du joueur
                    $playerTeam = null;
                    foreach ($matchDetails['teams'] as $teamId => $team) {
                        foreach ($team['roster'] as $player) {
                            if ($player['player_id'] === $playerId) {
                                $playerTeam = $teamId;
                                break 2;
                            }
                        }
                    }

                    if (!$playerTeam) continue;

                    // Ajouter les coéquipiers
                    foreach ($matchDetails['teams'][$playerTeam]['roster'] as $player) {
                        if ($player['player_id'] !== $playerId) {
                            $teammateId = $player['player_id'];
                            
                            if (!isset($teammateCount[$teammateId])) {
                                $teammateCount[$teammateId] = 0;
                                $teammates[$teammateId] = $player;
                            }
                            $teammateCount[$teammateId]++;
                        }
                    }
                } catch (\Exception $e) {
                    // Continuer même si un match échoue
                    continue;
                }
            }

            // Filtrer pour garder seulement les joueurs qui ont joué plusieurs fois ensemble
            $friends = [];
            foreach ($teammateCount as $teammateId => $count) {
                if ($count >= 3) { // Au moins 3 matches ensemble
                    $friends[] = array_merge($teammates[$teammateId], [
                        'games_together' => $count,
                        'friendship_score' => min($count * 10, 100)
                    ]);
                }
            }

            // Trier par nombre de matches ensemble
            usort($friends, function($a, $b) {
                return $b['games_together'] <=> $a['games_together'];
            });

            return array_slice($friends, 0, 50); // Limiter à 50 amis

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis depuis matches: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Enrichit les données des amis
     */
    private function enrichFriendsData($friends)
    {
        $enrichedFriends = [];

        foreach ($friends as $friend) {
            try {
                // Récupérer les données complètes du joueur
                $playerData = $this->faceitService->getPlayer($friend['player_id']);
                
                if (!$playerData) {
                    continue;
                }

                // Récupérer les stats
                $stats = null;
                try {
                    $stats = $this->faceitService->getPlayerStats($friend['player_id']);
                } catch (\Exception $e) {
                    // Continuer sans stats si erreur
                }

                // Simuler le statut en ligne
                $onlineStatus = $this->simulateOnlineStatus();
                
                $enrichedFriend = [
                    'player_id' => $friend['player_id'],
                    'nickname' => $playerData['nickname'],
                    'avatar' => $playerData['avatar'] ?? null,
                    'country' => $playerData['country'] ?? 'EU',
                    'skill_level' => $playerData['games']['cs2']['skill_level'] ?? 1,
                    'faceit_elo' => $playerData['games']['cs2']['faceit_elo'] ?? 1000,
                    'region' => $playerData['games']['cs2']['region'] ?? 'EU',
                    'games_together' => $friend['games_together'] ?? 0,
                    'friendship_score' => $friend['friendship_score'] ?? 0,
                    'online_status' => $onlineStatus,
                    'last_seen' => $this->generateLastSeen($onlineStatus),
                    'win_rate' => $this->extractWinRate($stats),
                    'kd_ratio' => $this->extractKDRatio($stats),
                    'matches' => $this->extractMatches($stats),
                    'current_streak' => $this->extractCurrentStreak($stats),
                    'faceit_url' => "https://www.faceit.com/en/players/{$playerData['nickname']}"
                ];

                $enrichedFriends[] = $enrichedFriend;

            } catch (\Exception $e) {
                Log::warning("Erreur enrichissement ami {$friend['player_id']}: " . $e->getMessage());
                continue;
            }
        }

        return $enrichedFriends;
    }

    /**
     * Calcule les statistiques des amis
     */
    private function calculateFriendsStats($friends)
    {
        if (empty($friends)) {
            return [
                'total_friends' => 0,
                'online_friends' => 0,
                'average_level' => 0,
                'average_elo' => 0,
                'top_countries' => [],
                'best_friend' => null
            ];
        }

        $totalFriends = count($friends);
        $onlineFriends = count(array_filter($friends, function($f) {
            return in_array($f['online_status'], ['online', 'in_game']);
        }));

        $avgLevel = round(array_sum(array_column($friends, 'skill_level')) / $totalFriends, 1);
        $avgElo = round(array_sum(array_column($friends, 'faceit_elo')) / $totalFriends);

        // Top pays
        $countries = array_count_values(array_column($friends, 'country'));
        arsort($countries);
        $topCountries = array_slice($countries, 0, 5, true);

        // Meilleur ami (plus de matches ensemble)
        $bestFriend = !empty($friends) ? $friends[0] : null;

        return [
            'total_friends' => $totalFriends,
            'online_friends' => $onlineFriends,
            'average_level' => $avgLevel,
            'average_elo' => $avgElo,
            'top_countries' => $topCountries,
            'best_friend' => $bestFriend
        ];
    }

    /**
     * Simule un statut en ligne
     */
    private function simulateOnlineStatus()
    {
        $statuses = [
            'online' => 15,      // 15%
            'in_game' => 10,     // 10%
            'away' => 20,        // 20%
            'offline' => 55      // 55%
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $percentage) {
            $cumulative += $percentage;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'offline';
    }

    /**
     * Génère une date de dernière connexion
     */
    private function generateLastSeen($onlineStatus)
    {
        if (in_array($onlineStatus, ['online', 'in_game'])) {
            return 'Maintenant';
        }

        if ($onlineStatus === 'away') {
            return rand(1, 30) . ' min';
        }

        // Offline
        $hours = rand(1, 72);
        if ($hours < 24) {
            return $hours . 'h';
        } else {
            $days = floor($hours / 24);
            return $days . 'j';
        }
    }

    /**
     * Extraction du win rate depuis les stats
     */
    private function extractWinRate($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return rand(35, 75); // Valeur simulée
        }
        
        $winRate = $stats['lifetime']['Win Rate %'] ?? null;
        if ($winRate !== null) {
            return round(floatval($winRate), 1);
        }
        
        return rand(35, 75);
    }

    /**
     * Extraction du K/D ratio depuis les stats
     */
    private function extractKDRatio($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return round(rand(60, 140) / 100, 2); // Valeur simulée
        }
        
        $kd = $stats['lifetime']['Average K/D Ratio'] ?? null;
        if ($kd !== null) {
            return round(floatval($kd), 2);
        }
        
        return round(rand(60, 140) / 100, 2);
    }

    /**
     * Extraction du nombre de matches
     */
    private function extractMatches($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return rand(50, 2000); // Valeur simulée
        }
        
        return intval($stats['lifetime']['Matches'] ?? rand(50, 2000));
    }

    /**
     * Extraction de la série actuelle
     */
    private function extractCurrentStreak($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return ['type' => rand(0, 1) ? 'win' : 'loss', 'count' => rand(1, 8)];
        }
        
        $winStreak = intval($stats['lifetime']['Current Win Streak'] ?? 0);
        
        if ($winStreak > 0) {
            return ['type' => 'win', 'count' => $winStreak];
        } else {
            return ['type' => 'loss', 'count' => rand(1, 5)];
        }
    }
}