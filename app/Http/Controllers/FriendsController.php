<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;
use App\Services\FaceitOAuthService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FriendsController extends Controller
{
    protected $faceitService;
    protected $faceitOAuth;

    public function __construct(FaceitService $faceitService, FaceitOAuthService $faceitOAuth)
    {
        $this->faceitService = $faceitService;
        $this->faceitOAuth = $faceitOAuth;
        $this->middleware('faceit.auth');
    }

    /**
     * Affiche la page des amis
     */
    public function index()
    {
        return view('friends');
    }

    /**
     * API - Récupère la liste des amis du joueur connecté
     */
    public function getFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user || !isset($user['player_data'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données utilisateur non disponibles'
                ], 400);
            }

            $playerId = $user['player_data']['player_id'];
            $cacheKey = "friends_list_{$playerId}";
            
            // Vérifier le cache (2 minutes)
            if (!$request->has('_refresh')) {
                $cachedFriends = Cache::get($cacheKey);
                if ($cachedFriends) {
                    return response()->json([
                        'success' => true,
                        'friends' => $cachedFriends,
                        'cached' => true
                    ]);
                }
            }

            // Récupérer les données du joueur pour avoir la liste d'amis
            $playerData = $this->faceitService->getPlayer($playerId);
            
            if (!$playerData || !isset($playerData['friends_ids'])) {
                return response()->json([
                    'success' => true,
                    'friends' => [],
                    'message' => 'Aucun ami trouvé'
                ]);
            }

            $friendIds = $playerData['friends_ids'];
            
            if (empty($friendIds)) {
                return response()->json([
                    'success' => true,
                    'friends' => [],
                    'message' => 'Aucun ami trouvé'
                ]);
            }

            // Récupérer les détails des amis (optimisé)
            $friends = $this->fetchFriendsDetails($friendIds);
            
            // Mettre en cache
            Cache::put($cacheKey, $friends, 120); // 2 minutes

            return response()->json([
                'success' => true,
                'friends' => $friends,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des amis: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des amis'
            ], 500);
        }
    }

    /**
     * API - Récupère les statistiques rapides des amis
     */
    public function getFriendsStats(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user || !isset($user['player_data'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données utilisateur non disponibles'
                ], 400);
            }

            $playerId = $user['player_data']['player_id'];
            $cacheKey = "friends_stats_{$playerId}";
            
            // Vérifier le cache (5 minutes)
            if (!$request->has('_refresh')) {
                $cachedStats = Cache::get($cacheKey);
                if ($cachedStats) {
                    return response()->json([
                        'success' => true,
                        'stats' => $cachedStats,
                        'cached' => true
                    ]);
                }
            }

            // Récupérer les amis
            $friendsResponse = $this->getFriends($request);
            $friendsData = $friendsResponse->getData(true);
            
            if (!$friendsData['success']) {
                return $friendsResponse;
            }

            $friends = $friendsData['friends'];
            $stats = $this->calculateFriendsStats($friends);
            
            // Mettre en cache
            Cache::put($cacheKey, $stats, 300); // 5 minutes

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des stats amis: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }

    /**
     * API - Compare le joueur avec ses amis
     */
    public function compareWithFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user || !isset($user['player_data'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données utilisateur non disponibles'
                ], 400);
            }

            $playerId = $user['player_data']['player_id'];
            $cacheKey = "friends_comparison_{$playerId}";
            
            // Vérifier le cache (10 minutes)
            if (!$request->has('_refresh')) {
                $cachedComparison = Cache::get($cacheKey);
                if ($cachedComparison) {
                    return response()->json([
                        'success' => true,
                        'comparison' => $cachedComparison,
                        'cached' => true
                    ]);
                }
            }

            // Récupérer les données du joueur
            $playerStats = $this->faceitService->getPlayerStats($playerId);
            
            // Récupérer les amis
            $friendsResponse = $this->getFriends($request);
            $friendsData = $friendsResponse->getData(true);
            
            if (!$friendsData['success']) {
                return $friendsResponse;
            }

            $friends = $friendsData['friends'];
            $comparison = $this->generateFriendsComparison($playerStats, $friends);
            
            // Mettre en cache
            Cache::put($cacheKey, $comparison, 600); // 10 minutes

            return response()->json([
                'success' => true,
                'comparison' => $comparison,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la comparaison avec les amis: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la comparaison'
            ], 500);
        }
    }

    /**
     * Récupère les détails des amis de manière optimisée
     */
    private function fetchFriendsDetails(array $friendIds)
    {
        $friends = [];
        $batchSize = 10; // Traiter par lots pour éviter la surcharge
        
        // Limiter le nombre d'amis pour éviter les timeouts
        $friendIds = array_slice($friendIds, 0, 50);
        
        foreach (array_chunk($friendIds, $batchSize) as $batch) {
            $batchPromises = [];
            
            foreach ($batch as $friendId) {
                try {
                    $friend = $this->faceitService->getPlayer($friendId);
                    
                    if ($friend && isset($friend['games']['cs2'])) {
                        $friends[] = [
                            'player_id' => $friend['player_id'],
                            'nickname' => $friend['nickname'],
                            'avatar' => $friend['avatar'],
                            'country' => $friend['country'],
                            'level' => $friend['games']['cs2']['skill_level'] ?? 1,
                            'elo' => $friend['games']['cs2']['faceit_elo'] ?? 1000,
                            'region' => $friend['games']['cs2']['region'] ?? 'EU',
                            'faceit_url' => $friend['faceit_url'] ?? null,
                            'verified' => $friend['verified'] ?? false,
                            'last_seen' => $this->calculateLastSeen($friend),
                            'game_data' => $friend['games']['cs2']
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning("Erreur lors de la récupération de l'ami {$friendId}: " . $e->getMessage());
                    continue;
                }
            }
            
            // Petite pause entre les lots
            if (count($batch) === $batchSize) {
                usleep(100000); // 100ms
            }
        }
        
        // Trier par ELO descendant
        usort($friends, function($a, $b) {
            return $b['elo'] - $a['elo'];
        });
        
        return $friends;
    }

    /**
     * Calcule les statistiques des amis
     */
    private function calculateFriendsStats(array $friends)
    {
        if (empty($friends)) {
            return [
                'total_friends' => 0,
                'average_elo' => 0,
                'average_level' => 0,
                'highest_elo' => 0,
                'lowest_elo' => 0,
                'level_distribution' => [],
                'country_distribution' => [],
                'region_distribution' => []
            ];
        }

        $totalFriends = count($friends);
        $totalElo = array_sum(array_column($friends, 'elo'));
        $totalLevel = array_sum(array_column($friends, 'level'));
        
        $levelDistribution = [];
        $countryDistribution = [];
        $regionDistribution = [];
        
        foreach ($friends as $friend) {
            // Distribution par niveau
            $level = $friend['level'];
            if (!isset($levelDistribution[$level])) {
                $levelDistribution[$level] = 0;
            }
            $levelDistribution[$level]++;
            
            // Distribution par pays
            $country = $friend['country'] ?? 'Unknown';
            if (!isset($countryDistribution[$country])) {
                $countryDistribution[$country] = 0;
            }
            $countryDistribution[$country]++;
            
            // Distribution par région
            $region = $friend['region'] ?? 'Unknown';
            if (!isset($regionDistribution[$region])) {
                $regionDistribution[$region] = 0;
            }
            $regionDistribution[$region]++;
        }
        
        // Trier les distributions
        arsort($levelDistribution);
        arsort($countryDistribution);
        arsort($regionDistribution);
        
        return [
            'total_friends' => $totalFriends,
            'average_elo' => round($totalElo / $totalFriends),
            'average_level' => round($totalLevel / $totalFriends, 1),
            'highest_elo' => max(array_column($friends, 'elo')),
            'lowest_elo' => min(array_column($friends, 'elo')),
            'level_distribution' => $levelDistribution,
            'country_distribution' => array_slice($countryDistribution, 0, 5, true),
            'region_distribution' => $regionDistribution
        ];
    }

    /**
     * Génère une comparaison avec les amis
     */
    private function generateFriendsComparison(array $playerStats, array $friends)
    {
        $playerElo = 1000; // Valeur par défaut
        $playerLevel = 1;
        $playerWinRate = 0;
        $playerKD = 0;
        
        // Extraire les stats du joueur
        if (isset($playerStats['lifetime'])) {
            $playerWinRate = floatval($playerStats['lifetime']['Win Rate %'] ?? 0);
            $playerKD = floatval($playerStats['lifetime']['Average K/D Ratio'] ?? 0);
        }
        
        // Comparaisons
        $betterThanCount = 0;
        $sameEloCount = 0;
        $worseThanCount = 0;
        
        foreach ($friends as $friend) {
            if ($friend['elo'] < $playerElo) {
                $betterThanCount++;
            } elseif ($friend['elo'] === $playerElo) {
                $sameEloCount++;
            } else {
                $worseThanCount++;
            }
        }
        
        $totalFriends = count($friends);
        $ranking = $betterThanCount + 1;
        
        return [
            'player_ranking' => $ranking,
            'total_friends' => $totalFriends,
            'better_than_percentage' => $totalFriends > 0 ? round(($betterThanCount / $totalFriends) * 100, 1) : 0,
            'top_friends' => array_slice($friends, 0, 3),
            'closest_friends' => $this->findClosestFriends($friends, $playerElo, 3),
            'improvement_potential' => $this->calculateImprovementPotential($playerStats, $friends)
        ];
    }

    /**
     * Trouve les amis les plus proches en terme d'ELO
     */
    private function findClosestFriends(array $friends, int $playerElo, int $limit = 3)
    {
        $friendsWithDistance = array_map(function($friend) use ($playerElo) {
            $friend['elo_distance'] = abs($friend['elo'] - $playerElo);
            return $friend;
        }, $friends);
        
        usort($friendsWithDistance, function($a, $b) {
            return $a['elo_distance'] - $b['elo_distance'];
        });
        
        return array_slice($friendsWithDistance, 0, $limit);
    }

    /**
     * Calcule le potentiel d'amélioration
     */
    private function calculateImprovementPotential(array $playerStats, array $friends)
    {
        $averageElo = array_sum(array_column($friends, 'elo')) / count($friends);
        $maxElo = max(array_column($friends, 'elo'));
        
        return [
            'average_gap' => max(0, $averageElo - 1000),
            'max_gap' => max(0, $maxElo - 1000),
            'next_target' => $this->findNextTarget($friends, 1000)
        ];
    }

    /**
     * Trouve le prochain objectif d'ELO
     */
    private function findNextTarget(array $friends, int $playerElo)
    {
        $higherEloFriends = array_filter($friends, function($friend) use ($playerElo) {
            return $friend['elo'] > $playerElo;
        });
        
        if (empty($higherEloFriends)) {
            return null;
        }
        
        usort($higherEloFriends, function($a, $b) {
            return $a['elo'] - $b['elo'];
        });
        
        return $higherEloFriends[0];
    }

    /**
     * Calcule la dernière activité approximative
     */
    private function calculateLastSeen(array $player)
    {
        // Utiliser la date d'activation comme approximation
        if (isset($player['activated_at'])) {
            return $player['activated_at'];
        }
        
        return null;
    }
}