<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LeaderboardController extends Controller
{
    protected $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    /**
     * Affiche la page des classements
     */
    public function index(Request $request)
    {
        $region = $request->get('region', 'EU');
        $country = $request->get('country', '');
        $limit = (int) $request->get('limit', 20);

        // Validation des paramètres
        $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
        if (!in_array($region, $validRegions)) {
            $region = 'EU';
        }

        $validLimits = [20, 50, 100];
        if (!in_array($limit, $validLimits)) {
            $limit = 20;
        }

        return view('leaderboards', compact('region', 'country', 'limit'));
    }

    /**
     * API - Récupère le classement global (vraie API FACEIT)
     */
    public function getLeaderboard(Request $request)
    {
        try {
            $region = strtoupper($request->get('region', 'EU'));
            $country = strtoupper($request->get('country', ''));
            $limit = (int) $request->get('limit', 20);
            $offset = (int) $request->get('offset', 0);

            // Validation
            $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
            if (!in_array($region, $validRegions)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Région invalide'
                ], 400);
            }

            $validLimits = [20, 50, 100];
            if (!in_array($limit, $validLimits)) {
                $limit = 20;
            }

            // Clé de cache
            $cacheKey = "rankings_{$region}_{$country}_{$offset}_{$limit}";
            
            // Vérifier le cache (5 minutes)
            if (!$request->has('_refresh')) {
                $cachedData = Cache::get($cacheKey);
                if ($cachedData) {
                    return response()->json([
                        'success' => true,
                        'data' => $cachedData['data'],
                        'pagination' => $cachedData['pagination'],
                        'cached' => true
                    ]);
                }
            }

            // Appel à l'API FACEIT Rankings
            $params = [
                'offset' => $offset,
                'limit' => $limit
            ];
            
            if ($country) {
                $params['country'] = $country;
            }

            Log::info('Appel API FACEIT Rankings', [
                'region' => $region,
                'country' => $country,
                'offset' => $offset,
                'limit' => $limit
            ]);

            $rankingData = $this->faceitService->getGlobalRanking($region, $country, $offset, $limit);

            if (!$rankingData || !isset($rankingData['items'])) {
                throw new \Exception('Aucune donnée de classement disponible');
            }

            // Enrichir les données avec les informations détaillées des joueurs
            $enrichedPlayers = $this->enrichPlayerRankings($rankingData['items']);

            // Pagination
            $pagination = [
                'current_page' => floor($offset / $limit) + 1,
                'has_next' => count($rankingData['items']) >= $limit,
                'total_items' => $rankingData['end'] ?? ($offset + count($rankingData['items'])),
                'items_per_page' => $limit,
                'start' => $rankingData['start'] ?? $offset,
                'end' => $rankingData['end'] ?? ($offset + count($rankingData['items']))
            ];

            $result = [
                'data' => $enrichedPlayers,
                'pagination' => $pagination
            ];

            // Mettre en cache pour 5 minutes
            Cache::put($cacheKey, $result, 300);

            return response()->json([
                'success' => true,
                'data' => $enrichedPlayers,
                'pagination' => $pagination,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du classement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement du classement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Recherche un joueur dans le classement
     */
    public function searchPlayer(Request $request)
    {
        try {
            $nickname = $request->get('nickname', '');
            $region = strtoupper($request->get('region', 'EU'));
            $country = strtoupper($request->get('country', ''));

            if (empty($nickname)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nom du joueur requis'
                ], 400);
            }

            Log::info('Recherche joueur dans classement', [
                'nickname' => $nickname,
                'region' => $region,
                'country' => $country
            ]);

            // 1. Chercher le joueur par nickname
            $player = $this->faceitService->getPlayerByNickname($nickname);
            
            if (!$player || !isset($player['games']['cs2'])) {
                throw new \Exception("Ce joueur n'a pas de profil CS2");
            }

            // 2. Obtenir sa position dans le classement
            try {
                $playerRanking = $this->faceitService->getPlayerRanking(
                    $player['player_id'], 
                    $region, 
                    $country ? $country : null, 
                    20
                );
                
                $position = $playerRanking['position'] ?? null;
                $rankingItems = $playerRanking['items'] ?? [];
                
                // Trouver le joueur dans les items retournés
                $playerInRanking = null;
                foreach ($rankingItems as $item) {
                    if ($item['player_id'] === $player['player_id']) {
                        $playerInRanking = $item;
                        break;
                    }
                }
                
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer la position dans le classement: ' . $e->getMessage());
                $position = null;
                $playerInRanking = null;
            }

            // 3. Récupérer les statistiques détaillées
            try {
                $stats = $this->faceitService->getPlayerStats($player['player_id']);
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer les stats: ' . $e->getMessage());
                $stats = null;
            }

            // 4. Construire les données enrichies
            $gameData = $player['games']['cs2'];
            $enrichedPlayer = [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'avatar' => $player['avatar'] ?? null,
                'country' => $player['country'] ?? $region,
                'skill_level' => $gameData['skill_level'] ?? 1,
                'faceit_elo' => $playerInRanking['faceit_elo'] ?? $gameData['faceit_elo'] ?? 1000,
                'position' => $position ?? 'N/A',
                'region' => $gameData['region'] ?? $region,
                'win_rate' => $this->extractWinRate($stats),
                'kd_ratio' => $this->extractKDRatio($stats),
                'matches' => $this->extractMatches($stats),
                'recent_form' => $this->calculateRecentForm($stats)
            ];

            return response()->json([
                'success' => true,
                'player' => $enrichedPlayer,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche de joueur: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Récupère les stats d'une région
     */
    public function getRegionStats(Request $request)
    {
        try {
            $region = strtoupper($request->get('region', 'EU'));
            
            // Clé de cache
            $cacheKey = "region_stats_{$region}";
            
            if (!$request->has('_refresh')) {
                $cachedData = Cache::get($cacheKey);
                if ($cachedData) {
                    return response()->json([
                        'success' => true,
                        'data' => $cachedData,
                        'cached' => true
                    ]);
                }
            }

            // Récupérer un échantillon du classement pour calculer les stats
            $leaderboardData = $this->faceitService->getGlobalRanking($region, null, 0, 100);
            
            if (!$leaderboardData || !isset($leaderboardData['items'])) {
                throw new \Exception('Impossible de récupérer les données de classement');
            }

            $players = $leaderboardData['items'];
            $totalPlayers = count($players);
            
            // Calculer les statistiques
            $totalElo = 0;
            $countryStats = [];
            $levelStats = [];
            
            foreach ($players as $player) {
                $elo = $player['faceit_elo'] ?? 1000;
                $totalElo += $elo;
                
                $country = $player['country'] ?? 'Unknown';
                if (!isset($countryStats[$country])) {
                    $countryStats[$country] = 0;
                }
                $countryStats[$country]++;
                
                $level = $player['game_skill_level'] ?? 1;
                if (!isset($levelStats[$level])) {
                    $levelStats[$level] = 0;
                }
                $levelStats[$level]++;
            }
            
            $averageElo = $totalPlayers > 0 ? round($totalElo / $totalPlayers) : 1000;
            
            // Trier les pays par nombre de joueurs
            arsort($countryStats);
            $topCountries = array_slice($countryStats, 0, 5, true);
            
            // Niveau le plus représenté
            arsort($levelStats);
            $topLevel = array_key_first($levelStats);
            
            $stats = [
                'total_players' => $totalPlayers * 500, // Estimation
                'average_elo' => $averageElo,
                'top_countries' => $topCountries,
                'top_level' => $topLevel,
                'level_distribution' => $levelStats
            ];

            // Mettre en cache (10 minutes)
            Cache::put($cacheKey, $stats, 600);

            return response()->json([
                'success' => true,
                'data' => $stats,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des stats de région: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }

    /**
     * Enrichit les données de classement avec des informations détaillées
     */
    private function enrichPlayerRankings(array $rankings)
    {
        $enrichedPlayers = [];
        
        foreach ($rankings as $ranking) {
            try {
                // Utiliser les données directes du ranking FACEIT
                $enrichedPlayer = [
                    'player_id' => $ranking['player_id'],
                    'nickname' => $ranking['nickname'],
                    'position' => $ranking['position'],
                    'faceit_elo' => $ranking['faceit_elo'],
                    'skill_level' => $ranking['game_skill_level'],
                    'country' => $ranking['country'] ?? 'EU',
                    'avatar' => null, // Sera récupéré si besoin
                    'win_rate' => 0, // Sera calculé si besoin
                    'kd_ratio' => 0, // Sera calculé si besoin
                    'matches' => 0,
                    'recent_form' => 'unknown'
                ];

                // Pour les 20 premiers, on peut récupérer plus de détails
                if ($ranking['position'] <= 20) {
                    try {
                        $playerDetails = $this->faceitService->getPlayer($ranking['player_id']);
                        if ($playerDetails) {
                            $enrichedPlayer['avatar'] = $playerDetails['avatar'] ?? null;
                        }
                        
                        // Stats basiques seulement pour économiser les appels API
                        $stats = $this->faceitService->getPlayerStats($ranking['player_id']);
                        if ($stats) {
                            $enrichedPlayer['win_rate'] = $this->extractWinRate($stats);
                            $enrichedPlayer['kd_ratio'] = $this->extractKDRatio($stats);
                            $enrichedPlayer['matches'] = $this->extractMatches($stats);
                            $enrichedPlayer['recent_form'] = $this->calculateRecentForm($stats);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Impossible d'enrichir les données pour {$ranking['player_id']}: " . $e->getMessage());
                    }
                }

                $enrichedPlayers[] = $enrichedPlayer;
                
            } catch (\Exception $e) {
                Log::warning("Erreur lors de l'enrichissement du joueur {$ranking['player_id']}: " . $e->getMessage());
                
                // Fallback avec données basiques
                $enrichedPlayers[] = [
                    'player_id' => $ranking['player_id'],
                    'nickname' => $ranking['nickname'],
                    'position' => $ranking['position'],
                    'faceit_elo' => $ranking['faceit_elo'],
                    'skill_level' => $ranking['game_skill_level'],
                    'country' => $ranking['country'] ?? 'EU',
                    'avatar' => null,
                    'win_rate' => 0,
                    'kd_ratio' => 0,
                    'matches' => 0,
                    'recent_form' => 'unknown'
                ];
            }
        }
        
        return $enrichedPlayers;
    }

    /**
     * Extraction du win rate depuis les stats
     */
    private function extractWinRate($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 0;
        }
        
        $winRate = $stats['lifetime']['Win Rate %'] ?? null;
        if ($winRate !== null) {
            return round(floatval($winRate), 1);
        }
        
        $matches = intval($stats['lifetime']['Matches'] ?? 0);
        $wins = intval($stats['lifetime']['Wins'] ?? 0);
        
        if ($matches > 0) {
            return round(($wins / $matches) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Extraction du K/D ratio depuis les stats
     */
    private function extractKDRatio($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 0;
        }
        
        $kd = $stats['lifetime']['Average K/D Ratio'] ?? null;
        if ($kd !== null) {
            return round(floatval($kd), 2);
        }
        
        $kills = intval($stats['lifetime']['Kills'] ?? 0);
        $deaths = intval($stats['lifetime']['Deaths'] ?? 0);
        
        if ($deaths > 0) {
            return round($kills / $deaths, 2);
        }
        
        return 0;
    }

    /**
     * Extraction du nombre de matches
     */
    private function extractMatches($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 0;
        }
        
        return intval($stats['lifetime']['Matches'] ?? 0);
    }

    /**
     * Calcul de la forme récente
     */
    private function calculateRecentForm($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 'unknown';
        }
        
        $recentResults = $stats['lifetime']['Recent Results'] ?? [];
        
        if (empty($recentResults)) {
            return 'unknown';
        }
        
        $wins = 0;
        foreach ($recentResults as $result) {
            if ($result === "1") {
                $wins++;
            }
        }
        
        $totalGames = count($recentResults);
        if ($totalGames === 0) {
            return 'unknown';
        }
        
        $winRate = ($wins / $totalGames) * 100;
        
        if ($winRate >= 80) return 'excellent';
        if ($winRate >= 60) return 'good';
        if ($winRate >= 40) return 'average';
        return 'poor';
    }
}