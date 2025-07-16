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
     * API - Récupère le classement (utilise FaceitService)
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

            // ✅ DÉLÉGUER au FaceitService (pas de duplication)
            $leaderboardData = $this->faceitService->getLeaderboardsWithFullData($region, $country, $offset, $limit);

            if (!$leaderboardData || !isset($leaderboardData['items'])) {
                throw new \Exception('Aucune donnée de classement disponible');
            }

            // Les données sont déjà enrichies par le service (forme récente incluse)
            $players = $leaderboardData['items'];

            // Pagination
            $pagination = [
                'current_page' => floor($offset / $limit) + 1,
                'has_next' => count($players) >= $limit,
                'total_items' => $offset + count($players),
                'items_per_page' => $limit
            ];

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
     * API - Recherche un joueur (utilise FaceitService)
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

            // ✅ DÉLÉGUER au FaceitService (toute la logique y est)
            $playerData = $this->faceitService->searchPlayerOptimized($nickname, $region);

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
}