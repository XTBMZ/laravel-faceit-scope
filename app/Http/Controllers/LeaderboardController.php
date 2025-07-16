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

    public function index(Request $request)
    {
        $region = $request->get('region', 'EU');
        $country = $request->get('country');
        $limit = $request->get('limit', 20);
        
        return view('leaderboards', compact('region', 'country', 'limit'));
    }

    /**
     * API: Récupération du classement avec données complètes
     */
    public function getLeaderboard(Request $request)
    {
        try {
            $region = $request->get('region', 'EU');
            $country = $request->get('country');
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
            
            $limit = max(10, min(100, $limit));
            $offset = max(0, $offset);
            
            // Récupération avec données complètes
            $leaderboard = $this->faceitService->getLeaderboardsWithFullData($region, $country, $offset, $limit);
            
            if (!isset($leaderboard['items'])) {
                throw new \Exception('Aucune donnée reçue de FACEIT');
            }
            
            // Filtrer les joueurs avec des données manquantes
            $validItems = array_filter($leaderboard['items'], function($item) {
                return isset($item['player_id'], $item['nickname'], $item['faceit_elo']);
            });
            
            return response()->json([
                'success' => true,
                'data' => array_values($validItems),
                'pagination' => [
                    'current_page' => floor($offset / $limit) + 1,
                    'limit' => $limit,
                    'offset' => $offset,
                    'has_next' => count($validItems) === $limit
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur LeaderboardController::getLeaderboard: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement du classement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Recherche optimisée d'un joueur
     */
    public function searchPlayer(Request $request)
    {
        try {
            $nickname = $request->get('nickname');
            $region = $request->get('region', 'EU');
            
            if (!$nickname) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nom de joueur requis'
                ], 400);
            }
            
            $player = $this->faceitService->searchPlayerOptimized($nickname, $region);
            
            return response()->json([
                'success' => true,
                'player' => $player
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur recherche joueur: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * API: Statistiques de région optimisées
     */
    public function getRegionStats(Request $request)
    {
        try {
            $region = $request->get('region', 'EU');
            
            $cacheKey = "region_stats_optimized_{$region}";
            
            $stats = Cache::remember($cacheKey, 3600, function () use ($region) {
                try {
                    // Récupérer un échantillon plus large pour des stats plus précises
                    $leaderboard = $this->faceitService->getLeaderboardsWithFullData($region, null, 0, 100);
                    $items = $leaderboard['items'] ?? [];
                    
                    if (empty($items)) {
                        return [
                            'total_players' => 0,
                            'average_elo' => 0,
                            'top_countries' => []
                        ];
                    }
                    
                    $totalElo = 0;
                    $countries = [];
                    
                    foreach ($items as $item) {
                        $elo = $item['faceit_elo'] ?? 1000;
                        $totalElo += $elo;
                        
                        $country = $item['country'] ?? 'Unknown';
                        $countries[$country] = ($countries[$country] ?? 0) + 1;
                    }
                    
                    arsort($countries);
                    
                    return [
                        'total_players' => count($items),
                        'average_elo' => round($totalElo / count($items)),
                        'top_countries' => array_slice($countries, 0, 5, true)
                    ];
                    
                } catch (\Exception $e) {
                    Log::error('Erreur stats région optimisées: ' . $e->getMessage());
                    return [
                        'total_players' => 0,
                        'average_elo' => 0,
                        'top_countries' => []
                    ];
                }
            });
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}