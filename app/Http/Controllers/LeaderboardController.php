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
     * API: Récupération du classement optimisé
     */
    public function getLeaderboard(Request $request)
    {
        try {
            // Validation simple
            $region = $request->get('region', 'EU');
            $country = $request->get('country');
            $limit = (int) $request->get('limit', 20);
            $offset = (int) $request->get('offset', 0);
            
            // Valider la région
            $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
            if (!in_array($region, $validRegions)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Région invalide'
                ], 400);
            }
            
            // Limiter les valeurs
            $limit = max(10, min(100, $limit));
            $offset = max(0, $offset);
            
            // Cache key
            $cacheKey = "leaderboard_{$region}_{$country}_{$limit}_{$offset}";
            
            // Récupération avec cache (5 minutes)
            $data = Cache::remember($cacheKey, 300, function () use ($region, $country, $limit, $offset) {
                try {
                    // Appel à l'API FACEIT
                    $leaderboard = $this->faceitService->getLeaderboards($region, $country, $offset, $limit);
                    
                    if (!isset($leaderboard['items'])) {
                        throw new \Exception('Aucune donnée reçue de FACEIT');
                    }
                    
                    $items = $leaderboard['items'];
                    $enrichedItems = [];
                    
                    foreach ($items as $index => $item) {
                        // ✅ CORRECTION : Vérification sécurisée des clés
                        $playerId = $item['player_id'] ?? '';
                        $nickname = $item['nickname'] ?? 'Joueur inconnu';
                        $country = $item['country'] ?? 'EU';
                        
                        // ⚠️ L'API des classements peut ne pas avoir skill_level directement
                        // On l'extrait de différentes façons possibles
                        $skillLevel = $this->extractSkillLevel($item);
                        $faceitElo = $this->extractFaceitElo($item);
                        
                        $enrichedItems[] = [
                            'player_id' => $playerId,
                            'nickname' => $nickname,
                            'avatar' => $this->generateDefaultAvatar(),
                            'country' => $country,
                            'skill_level' => $skillLevel,
                            'faceit_elo' => $faceitElo,
                            'region' => $item['region'] ?? $region,
                            'position' => $offset + $index + 1,
                            'win_rate' => $this->estimateWinRate($faceitElo, $skillLevel),
                            'kd_ratio' => $this->estimateKDRatio($faceitElo, $skillLevel),
                            'matches' => $skillLevel * 50,
                            'recent_form' => $this->estimateForm($faceitElo, $skillLevel)
                        ];
                    }
                    
                    return $enrichedItems;
                    
                } catch (\Exception $e) {
                    \Log::error('Erreur API FACEIT leaderboard: ' . $e->getMessage());
                    \Log::error('Données reçues: ' . json_encode($leaderboard ?? 'null'));
                    throw $e;
                }
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => floor($offset / $limit) + 1,
                    'limit' => $limit,
                    'offset' => $offset,
                    'has_next' => count($data) === $limit
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur LeaderboardController::getLeaderboard: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement du classement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extraction sécurisée du skill level depuis les données FACEIT
     */
    private function extractSkillLevel($item)
    {
        // Méthode 1: skill_level direct
        if (isset($item['skill_level'])) {
            return (int) $item['skill_level'];
        }
        
        // Méthode 2: dans games.cs2
        if (isset($item['games']['cs2']['skill_level'])) {
            return (int) $item['games']['cs2']['skill_level'];
        }
        
        // Méthode 3: calculé depuis l'ELO
        $elo = $this->extractFaceitElo($item);
        return $this->calculateSkillLevelFromElo($elo);
    }

    /**
     * Extraction sécurisée de l'ELO depuis les données FACEIT
     */
    private function extractFaceitElo($item)
    {
        // Méthode 1: faceit_elo direct
        if (isset($item['faceit_elo'])) {
            return (int) $item['faceit_elo'];
        }
        
        // Méthode 2: dans games.cs2
        if (isset($item['games']['cs2']['faceit_elo'])) {
            return (int) $item['games']['cs2']['faceit_elo'];
        }
        
        // Méthode 3: valeur par défaut
        return 1000;
    }

    /**
     * Calcul du skill level basé sur l'ELO
     */
    private function calculateSkillLevelFromElo($elo)
    {
        if ($elo >= 3000) return 10;
        if ($elo >= 2500) return 9;
        if ($elo >= 2000) return 8;
        if ($elo >= 1750) return 7;
        if ($elo >= 1500) return 6;
        if ($elo >= 1250) return 5;
        if ($elo >= 1000) return 4;
        if ($elo >= 800) return 3;
        if ($elo >= 600) return 2;
        return 1;
    }

    /**
     * API: Recherche d'un joueur
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
            
            // Cache pour la recherche
            $cacheKey = "player_search_{$nickname}_{$region}";
            
            $result = Cache::remember($cacheKey, 300, function () use ($nickname, $region) {
                try {
                    $player = $this->faceitService->getPlayerByNickname($nickname);
                    
                    if (!isset($player['games']['cs2'])) {
                        throw new \Exception("Ce joueur n'a pas de profil CS2");
                    }
                    
                    // Essayer de récupérer le rang (optionnel)
                    $position = 'N/A';
                    try {
                        $ranking = $this->faceitService->getPlayerRanking($player['player_id'], $region);
                        $position = $ranking['position'] ?? 'N/A';
                    } catch (\Exception $e) {
                        // Ignorer l'erreur de ranking
                        \Log::info('Pas de ranking pour ' . $nickname . ': ' . $e->getMessage());
                    }
                    
                    $elo = $player['games']['cs2']['faceit_elo'] ?? 1000;
                    $level = $player['games']['cs2']['skill_level'] ?? $this->calculateSkillLevelFromElo($elo);
                    
                    return [
                        'player_id' => $player['player_id'],
                        'nickname' => $player['nickname'],
                        'avatar' => $player['avatar'] ?? $this->generateDefaultAvatar(),
                        'country' => $player['country'] ?? 'EU',
                        'skill_level' => $level,
                        'faceit_elo' => $elo,
                        'region' => $player['games']['cs2']['region'] ?? $region,
                        'position' => $position,
                        'win_rate' => $this->estimateWinRate($elo, $level),
                        'kd_ratio' => $this->estimateKDRatio($elo, $level),
                    ];
                    
                } catch (\Exception $e) {
                    throw $e;
                }
            });
            
            return response()->json([
                'success' => true,
                'player' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * API: Statistiques de région
     */
    public function getRegionStats(Request $request)
    {
        try {
            $region = $request->get('region', 'EU');
            
            $cacheKey = "region_stats_{$region}";
            
            $stats = Cache::remember($cacheKey, 3600, function () use ($region) { // 1 heure
                try {
                    $leaderboard = $this->faceitService->getLeaderboards($region, null, 0, 50);
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
                        $elo = $this->extractFaceitElo($item);
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
                    \Log::error('Erreur stats région: ' . $e->getMessage());
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

    // Méthodes utilitaires
    private function estimateWinRate($elo, $level)
    {
        $base = 35 + ($level * 3.5);
        $eloBonus = ($elo - 1000) / 30;
        return max(15, min(85, round($base + $eloBonus, 1)));
    }

    private function estimateKDRatio($elo, $level)
    {
        $base = 0.6 + ($level * 0.09);
        $eloBonus = ($elo - 1000) / 1500;
        return max(0.2, min(3.0, round($base + $eloBonus, 2)));
    }

    private function estimateForm($elo, $level)
    {
        // Estimer le winrate du joueur basé sur son ELO/niveau
        $estimatedWinRate = $this->estimateWinRate($elo, $level) / 100; // Convertir en décimal
        
        // Générer 5 résultats "récents" de façon déterministe (même résultat à chaque fois pour le même joueur)
        $seed = ($elo * 7 + $level * 13) % 1000; // Seed basé sur ELO et niveau
        $recentResults = [];
        
        for ($i = 0; $i < 5; $i++) {
            // Générer un nombre pseudo-aléatoire déterministe
            $seed = ($seed * 16807) % 2147483647;
            $random = $seed / 2147483647;
            
            // Victoire si random < winrate estimé
            $recentResults[] = $random < $estimatedWinRate ? 1 : 0;
        }
        
        $wins = array_sum($recentResults);
        Log::info("Forme récente pour ELO $elo, niveau $level: " . implode(',', $recentResults) . " (victoires: $wins)");

        // Classification selon vos critères
        if ($wins == 5) return 'excellent';           // 5 victoires
        if ($wins >= 3) return 'good';                // 3-4 victoires (1-2 défaites max)
        if ($wins >= 2) return 'average';             // 2 victoires
        return 'poor';                                // 0-1 victoire
    }

    private function generateDefaultAvatar()
    {
        return 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    }
}