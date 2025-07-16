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
     * API - Récupère le classement
     */
    public function getLeaderboard(Request $request)
    {
        try {
            $region = $request->get('region', 'EU');
            $country = $request->get('country', '');
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

            // Clé de cache unique
            $cacheKey = "leaderboard_{$region}_{$country}_{$limit}_{$offset}";
            
            // Vérifier le cache (3 minutes)
            $cachedData = Cache::get($cacheKey);
            if ($cachedData && !$request->has('_t')) {
                return response()->json([
                    'success' => true,
                    'data' => $cachedData['data'],
                    'pagination' => $cachedData['pagination'],
                    'cached' => true
                ]);
            }

            // Récupération des données depuis FACEIT
            $leaderboardData = $this->faceitService->getGlobalRanking($region, $country, $offset, $limit);

            if (!$leaderboardData || !isset($leaderboardData['items'])) {
                throw new \Exception('Aucune donnée de classement disponible');
            }

            // Traitement des données - Version optimisée
            $players = collect($leaderboardData['items'])->map(function ($item, $index) use ($offset) {
                return [
                    'player_id' => $item['player_id'] ?? '',
                    'nickname' => $item['nickname'] ?? 'Joueur inconnu',
                    'avatar' => $item['avatar'] ?? '',
                    'country' => $item['country'] ?? 'EU',
                    'faceit_elo' => $item['faceit_elo'] ?? 1000,
                    'skill_level' => $item['skill_level'] ?? 1,
                    'position' => $offset + $index + 1,
                    'win_rate' => 0, // On récupérera ces données à la demande
                    'kd_ratio' => 0, // Pour optimiser les performances
                    'recent_form' => 'unknown' // Données lourdes, on les récupère uniquement pour la recherche
                ];
            })->toArray();

            // Pagination
            $pagination = [
                'current_page' => floor($offset / $limit) + 1,
                'has_next' => count($players) >= $limit,
                'total_items' => $offset + count($players),
                'items_per_page' => $limit
            ];

            // Mettre en cache
            $cacheData = [
                'data' => $players,
                'pagination' => $pagination
            ];
            Cache::put($cacheKey, $cacheData, 180); // 3 minutes

            return response()->json([
                'success' => true,
                'data' => $players,
                'pagination' => $pagination,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du classement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement du classement'
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
            $region = $request->get('region', 'EU');

            if (empty($nickname)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nom du joueur requis'
                ], 400);
            }

            // Clé de cache
            $cacheKey = "player_search_{$nickname}_{$region}";
            $cachedData = Cache::get($cacheKey);
            
            if ($cachedData) {
                return response()->json([
                    'success' => true,
                    'player' => $cachedData,
                    'cached' => true
                ]);
            }

            // Rechercher le joueur
            $player = $this->faceitService->getPlayerByNickname($nickname);
            
            if (!$player) {
                return response()->json([
                    'success' => false,
                    'error' => 'Joueur non trouvé'
                ], 404);
            }

            // Vérifier s'il a un profil CS2
            if (!isset($player['games']['cs2']) && !isset($player['games']['csgo'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ce joueur n\'a pas de profil CS2'
                ], 404);
            }

            // Récupérer les stats pour la recherche spécifique
            $stats = null;
            $winRate = 0;
            $kdRatio = 0;
            $recentForm = 'unknown';
            
            try {
                $stats = $this->faceitService->getPlayerStats($player['player_id']);
                if ($stats && isset($stats['lifetime'])) {
                    $winRate = round(floatval($stats['lifetime']['Win Rate %'] ?? 0), 1);
                    $kdRatio = round(floatval($stats['lifetime']['Average K/D Ratio'] ?? 0), 2);
                    
                    // Analyser la forme récente depuis Recent Results
                    $recentResults = $stats['lifetime']['Recent Results'] ?? [];
                    if (!empty($recentResults)) {
                        $recentForm = $this->analyzeRecentForm($recentResults);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Stats indisponibles pour {$nickname}: " . $e->getMessage());
            }

            // Essayer de récupérer la position dans le classement
            $position = 'N/A';
            try {
                $ranking = $this->faceitService->getPlayerRanking($player['player_id'], $region);
                if ($ranking && isset($ranking['position'])) {
                    $position = $ranking['position'];
                }
            } catch (\Exception $e) {
                Log::warning("Position indisponible pour {$nickname}: " . $e->getMessage());
            }

            $playerData = [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'avatar' => $player['avatar'] ?? '',
                'country' => $player['country'] ?? 'EU',
                'faceit_elo' => $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000,
                'skill_level' => $player['games']['cs2']['skill_level'] ?? $player['games']['csgo']['skill_level'] ?? 1,
                'position' => $position,
                'win_rate' => $winRate,
                'kd_ratio' => $kdRatio,
                'recent_form' => $recentForm
            ];

            // Mettre en cache (5 minutes)
            Cache::put($cacheKey, $playerData, 300);

            return response()->json([
                'success' => true,
                'player' => $playerData,
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
            $region = $request->get('region', 'EU');
            
            // Clé de cache
            $cacheKey = "region_stats_{$region}";
            $cachedData = Cache::get($cacheKey);
            
            if ($cachedData && !$request->has('_t')) {
                return response()->json([
                    'success' => true,
                    'data' => $cachedData,
                    'cached' => true
                ]);
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
            
            foreach ($players as $player) {
                $elo = $player['faceit_elo'] ?? 1000;
                $totalElo += $elo;
                
                $country = $player['country'] ?? 'Unknown';
                if (!isset($countryStats[$country])) {
                    $countryStats[$country] = 0;
                }
                $countryStats[$country]++;
            }
            
            $averageElo = $totalPlayers > 0 ? round($totalElo / $totalPlayers) : 1000;
            
            // Trier les pays par nombre de joueurs
            arsort($countryStats);
            $topCountries = array_slice($countryStats, 0, 5, true);
            
            $stats = [
                'total_players' => $totalPlayers * 100, // Estimation basée sur l'échantillon
                'average_elo' => $averageElo,
                'top_countries' => $topCountries
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
     * Analyse la forme récente d'un joueur
     */
    private function analyzeRecentForm(array $recentResults)
    {
        if (empty($recentResults)) {
            return 'unknown';
        }

        $totalGames = count($recentResults);
        $wins = array_sum(array_map('intval', $recentResults));
        $winRate = ($wins / $totalGames) * 100;

        if ($winRate >= 80) {
            return 'excellent';
        } elseif ($winRate >= 60) {
            return 'good';
        } elseif ($winRate >= 40) {
            return 'average';
        } else {
            return 'poor';
        }
    }
}