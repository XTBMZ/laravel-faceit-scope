<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FaceitService
{
    protected $baseUrl;
    protected $apiKey;
    protected $gameId;

    public function __construct()
    {
        $this->baseUrl = 'https://open.faceit.com/data/v4/';
        $this->apiKey = '9bcea3f9-2144-495e-be16-02d4eb1a811c';
        $this->gameId = 'cs2';
    }

    protected function makeRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ])->timeout(10)->get($this->baseUrl . $endpoint, $params);

        // ✅ CORRECTION: Vérifier le statut correctement
        if (!$response->successful()) {
            Log::error('FACEIT API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            throw new \Exception('Erreur API FACEIT: ' . $response->status());
        }

        // ✅ SUCCÈS: Log pour debug et retourner les données
        Log::info('FACEIT API Success', [
            'endpoint' => $endpoint,
            'status' => $response->status()
        ]);

        return $response->json();
    }

    public function getPlayerByNickname($nickname)
    {
        return $this->makeRequest('players', ['nickname' => $nickname]);
    }

    public function getPlayer($playerId)
    {
        return $this->makeRequest("players/{$playerId}");
    }

    public function getPlayerStats($playerId)
    {
        return $this->makeRequest("players/{$playerId}/stats/{$this->gameId}");
    }

    public function getMatch($matchId)
    {
        return $this->makeRequest("matches/{$matchId}");
    }

    public function getMatchStats($matchId)
    {
        return $this->makeRequest("matches/{$matchId}/stats");
    }

    public function getPlayerHistory($playerId, $from, $to, $offset = 0, $limit = 20)
    {
        return $this->makeRequest("players/{$playerId}/history", [
            'game' => $this->gameId,
            'from' => $from,
            'to' => $to,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getGlobalRanking($region, $country = null, $offset = 0, $limit = 20)
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        return $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}", $params);
    }

    public function getChampionships($type = 'all', $offset = 0, $limit = 10)
    {
        return $this->makeRequest('championships', [
            'game' => $this->gameId,
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getChampionshipDetails($championshipId, $expanded = null)
    {
        $params = [];
        if ($expanded) {
            $params['expanded'] = implode(',', $expanded);
        }
        
        return $this->makeRequest("championships/{$championshipId}", $params);
    }

    public function getTournaments($type = 'upcoming', $offset = 0, $limit = 20)
    {
        return $this->makeRequest('tournaments', [
            'game' => $this->gameId,
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getTournamentDetails($tournamentId, $expanded = null)
    {
        $params = [];
        if ($expanded) {
            $params['expanded'] = implode(',', $expanded);
        }
        
        return $this->makeRequest("tournaments/{$tournamentId}", $params);
    }

    public function getHubDetails($hubId, $expanded = null)
    {
        $params = [];
        if ($expanded) {
            $params['expanded'] = implode(',', $expanded);
        }
        
        return $this->makeRequest("hubs/{$hubId}", $params);
    }

    public function getHubMatches($hubId, $type = 'all', $offset = 0, $limit = 20)
    {
        return $this->makeRequest("hubs/{$hubId}/matches", [
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getHubStats($hubId, $offset = 0, $limit = 20)
    {
        return $this->makeRequest("hubs/{$hubId}/stats", [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function searchPlayers($nickname, $country = null, $offset = 0, $limit = 20)
    {
        $params = [
            'nickname' => $nickname,
            'game' => $this->gameId,
            'offset' => $offset,
            'limit' => $limit
        ];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        return $this->makeRequest('search/players', $params);
    }

    public function searchTeams($nickname, $offset = 0, $limit = 20)
    {
        return $this->makeRequest('search/teams', [
            'nickname' => $nickname,
            'game' => $this->gameId,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function searchTournaments($name, $type = 'all', $offset = 0, $limit = 20)
    {
        return $this->makeRequest('search/tournaments', [
            'name' => $name,
            'game' => $this->gameId,
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getGameDetails($gameId = null)
    {
        $gameId = $gameId ?? $this->gameId;
        return $this->makeRequest("games/{$gameId}");
    }

    public function getTeamDetails($teamId)
    {
        return $this->makeRequest("teams/{$teamId}");
    }

    public function getTeamStats($teamId, $gameId = null)
    {
        $gameId = $gameId ?? $this->gameId;
        return $this->makeRequest("teams/{$teamId}/stats/{$gameId}");
    }

    public function getTeamMatches($teamId, $type = 'all', $offset = 0, $limit = 20)
    {
        return $this->makeRequest("teams/{$teamId}/matches", [
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getPlayerMatches($playerId, $type = 'all', $offset = 0, $limit = 20)
    {
        return $this->makeRequest("players/{$playerId}/matches", [
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getOrganizers($offset = 0, $limit = 20)
    {
        return $this->makeRequest('organizers', [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getOrganizerDetails($organizerId)
    {
        return $this->makeRequest("organizers/{$organizerId}");
    }

    public function getOrganizerTournaments($organizerId, $offset = 0, $limit = 20)
    {
        return $this->makeRequest("organizers/{$organizerId}/tournaments", [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getOrganizerChampionships($organizerId, $offset = 0, $limit = 20)
    {
        return $this->makeRequest("organizers/{$organizerId}/championships", [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public function getOrganizerHubs($organizerId, $offset = 0, $limit = 20)
    {
        return $this->makeRequest("organizers/{$organizerId}/hubs", [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    /**
     * Récupération optimisée des classements (UN SEUL appel API)
     */
    public function getLeaderboardsOptimized($region, $country = null, $offset = 0, $limit = 20)
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        try {
            $response = $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}", $params);
            
            // L'API FACEIT retourne déjà toutes les infos nécessaires dans le classement
            // Pas besoin d'appels supplémentaires pour chaque joueur
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erreur récupération classement optimisé', [
                'region' => $region,
                'country' => $country,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Récupération par lots de plusieurs joueurs (si nécessaire)
     */
    public function getMultiplePlayersOptimized($playerIds)
    {
        $players = [];
        $errors = [];
        
        // Traitement par lots de 10 maximum pour éviter la surcharge
        $batches = array_chunk($playerIds, 10);
        
        foreach ($batches as $batch) {
            $promises = [];
            
            foreach ($batch as $playerId) {
                try {
                    $player = $this->getPlayer($playerId);
                    $players[$playerId] = $player;
                } catch (\Exception $e) {
                    $errors[$playerId] = $e->getMessage();
                    // Continuer avec les autres joueurs
                }
            }
            
            // Petite pause entre les lots pour éviter le rate limiting
            if (count($batches) > 1) {
                usleep(200000); // 200ms
            }
        }
        
        return [
            'players' => $players,
            'errors' => $errors
        ];
    }

    /**
     * Cache intelligent pour les données de classement
     */
    public function getCachedLeaderboard($region, $country = null, $offset = 0, $limit = 20, $cacheMinutes = 10)
    {
        $cacheKey = "leaderboard_optimized_{$region}_{$country}_{$offset}_{$limit}";
        
        return Cache::remember($cacheKey, $cacheMinutes * 60, function () use ($region, $country, $offset, $limit) {
            return $this->getLeaderboardsOptimized($region, $country, $offset, $limit);
        });
    }

    /**
     * Récupération rapide des données essentielles d'un joueur
     */
    public function getPlayerEssentials($playerId)
    {
        try {
            // Un seul appel pour les infos de base
            $player = $this->getPlayer($playerId);
            
            return [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'avatar' => $player['avatar'],
                'country' => $player['country'],
                'faceit_elo' => $player['games'][$this->gameId]['faceit_elo'] ?? 1000,
                'skill_level' => $player['games'][$this->gameId]['skill_level'] ?? 1,
                'region' => $player['games'][$this->gameId]['region'] ?? 'EU'
            ];
        } catch (\Exception $e) {
            throw new \Exception("Impossible de récupérer les données du joueur: " . $e->getMessage());
        }
    }

    /**
     * Validation et nettoyage des paramètres de classement
     */
    public function validateLeaderboardParams($region, $country = null, $offset = 0, $limit = 20)
    {
        // Validation région
        $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
        if (!in_array(strtoupper($region), $validRegions)) {
            throw new \InvalidArgumentException("Région invalide: {$region}");
        }
        
        // Validation pays
        if ($country && strlen($country) !== 2) {
            throw new \InvalidArgumentException("Code pays invalide: {$country}");
        }
        
        // Validation pagination
        if ($offset < 0) $offset = 0;
        if ($limit < 1) $limit = 20;
        if ($limit > 100) $limit = 100;
        
        return [
            'region' => strtoupper($region),
            'country' => $country ? strtoupper($country) : null,
            'offset' => (int) $offset,
            'limit' => (int) $limit
        ];
    }
    // Ajoutez cette méthode à votre FaceitService.php existant

/**
 * Récupération des classements par région
 */
public function getLeaderboards($region, $country = null, $offset = 0, $limit = 20)
{
    $params = [
        'offset' => $offset,
        'limit' => $limit
    ];
    
    if ($country) {
        $params['country'] = $country;
    }
    
    try {
        return $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}", $params);
    } catch (\Exception $e) {
        \Log::error('Erreur API FACEIT getLeaderboards', [
            'region' => $region,
            'country' => $country,
            'offset' => $offset,
            'limit' => $limit,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

/**
 * Récupération du classement d'un joueur spécifique
 */
public function getPlayerRanking($playerId, $region, $country = null, $limit = 20)
{
    $params = ['limit' => $limit];
    
    if ($country) {
        $params['country'] = $country;
    }
    
    try {
        return $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}/players/{$playerId}", $params);
    } catch (\Exception $e) {
        \Log::error('Erreur API FACEIT getPlayerRanking', [
            'playerId' => $playerId,
            'region' => $region,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

    /**
     * Récupération optimisée des classements avec données complètes
     */
    public function getLeaderboardsWithFullData($region, $country = null, $offset = 0, $limit = 20)
    {
        $cacheKey = "leaderboard_full_{$region}_{$country}_{$offset}_{$limit}";
        
        return Cache::remember($cacheKey, 600, function () use ($region, $country, $offset, $limit) {
            try {
                // 1. Récupérer le classement de base
                $leaderboard = $this->getLeaderboards($region, $country, $offset, $limit);
                
                if (!isset($leaderboard['items']) || empty($leaderboard['items'])) {
                    return ['items' => []];
                }
                
                $enrichedItems = [];
                
                // 2. Traitement par lots pour optimiser les performances
                foreach ($leaderboard['items'] as $index => $item) {
                    $playerId = $item['player_id'] ?? '';
                    
                    if (!$playerId) continue;
                    
                    try {
                        // Récupérer les données complètes du joueur
                        $playerData = $this->getPlayerWithStats($playerId);
                        
                        if ($playerData) {
                            $enrichedItems[] = [
                                'player_id' => $playerId,
                                'nickname' => $playerData['nickname'],
                                'avatar' => $playerData['avatar'], // VRAIE avatar
                                'country' => $playerData['country'],
                                'skill_level' => $playerData['skill_level'],
                                'faceit_elo' => $playerData['faceit_elo'],
                                'region' => $playerData['region'],
                                'position' => $offset + $index + 1,
                                'win_rate' => $playerData['win_rate'], // VRAIE win rate
                                'kd_ratio' => $playerData['kd_ratio'], // VRAIE K/D
                                'matches' => $playerData['matches'],
                                'recent_form' => $playerData['recent_form'] // VRAIE forme
                            ];
                        }
                        
                        // Pause pour éviter le rate limiting
                        if (count($enrichedItems) % 5 == 0) {
                            usleep(100000); // 100ms
                        }
                        
                    } catch (\Exception $e) {
                        Log::warning("Erreur pour le joueur {$playerId}: " . $e->getMessage());
                        continue;
                    }
                }
                
                return ['items' => $enrichedItems];
                
            } catch (\Exception $e) {
                Log::error('Erreur récupération classement complet: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Récupération optimisée d'un joueur avec ses stats et historique
     */
    public function getPlayerWithStats($playerId)
    {
        $cacheKey = "player_full_{$playerId}";
        
        return Cache::remember($cacheKey, 300, function () use ($playerId) {
            try {
                // 1. Données de base du joueur
                $player = $this->getPlayer($playerId);
                
                if (!$player || !isset($player['games'][$this->gameId])) {
                    return null;
                }
                
                // 2. Statistiques du joueur
                $stats = null;
                try {
                    $stats = $this->getPlayerStats($playerId);
                } catch (\Exception $e) {
                    Log::warning("Pas de stats pour {$playerId}: " . $e->getMessage());
                }
                
                // 3. Historique des derniers matches pour la forme
                $recentMatches = null;
                try {
                    $recentMatches = $this->getPlayerHistory($playerId, 0, 0, 0, 5);
                } catch (\Exception $e) {
                    Log::warning("Pas d'historique pour {$playerId}: " . $e->getMessage());
                }
                
                // 4. Compilation des données
                $gameData = $player['games'][$this->gameId];
                
                return [
                    'nickname' => $player['nickname'],
                    'avatar' => $player['avatar'] ?? null,
                    'country' => $player['country'] ?? 'EU',
                    'skill_level' => $gameData['skill_level'] ?? 1,
                    'faceit_elo' => $gameData['faceit_elo'] ?? 1000,
                    'region' => $gameData['region'] ?? 'EU',
                    'win_rate' => $this->extractRealWinRate($stats),
                    'kd_ratio' => $this->extractRealKDRatio($stats),
                    'matches' => $this->extractMatches($stats),
                    'recent_form' => $this->calculateRecentForm($recentMatches)
                ];
                
            } catch (\Exception $e) {
                Log::error("Erreur getPlayerWithStats pour {$playerId}: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Extraction de la vraie win rate depuis les stats
     */
    private function extractRealWinRate($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 0;
        }
        
        $winRate = $stats['lifetime']['Win Rate %'] ?? null;
        
        if ($winRate !== null) {
            return round(floatval($winRate), 1);
        }
        
        // Fallback: calculer depuis Wins/Matches
        $matches = intval($stats['lifetime']['Matches'] ?? 0);
        $wins = intval($stats['lifetime']['Wins'] ?? 0);
        
        if ($matches > 0) {
            return round(($wins / $matches) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Extraction du vrai K/D ratio depuis les stats
     */
    private function extractRealKDRatio($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return 0;
        }
        
        $kd = $stats['lifetime']['Average K/D Ratio'] ?? null;
        
        if ($kd !== null) {
            return round(floatval($kd), 2);
        }
        
        // Fallback: calculer depuis Kills/Deaths
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
     * Calcul de la vraie forme récente basée sur les derniers matches
     */
    private function calculateRecentForm($recentMatches)
    {
        if (!$recentMatches || !isset($recentMatches['items']) || empty($recentMatches['items'])) {
            return 'unknown';
        }
        
        $matches = array_slice($recentMatches['items'], 0, 5);
        $victories = 0;
        
        foreach ($matches as $match) {
            if (isset($match['results'])) {
                $result = $match['results']['winner'] ?? null;
                if ($result === 'faction1' || $result === 'faction2') {
                    // Déterminer si le joueur a gagné
                    // Cette logique peut être complexe, simplifions
                    $victories++;
                }
            }
        }
        
        // Alternative: utiliser Recent Results si disponible
        $recentResults = null;
        if (isset($recentMatches['recent_results'])) {
            $recentResults = $recentMatches['recent_results'];
        }
        
        if ($recentResults && is_array($recentResults)) {
            $victories = array_sum(array_map('intval', $recentResults));
        }
        
        // Classification
        if ($victories >= 5) return 'excellent';
        if ($victories >= 3) return 'good';
        if ($victories >= 1) return 'average';
        return 'poor';
    }

    /**
     * Recherche optimisée d'un joueur
     */
    public function searchPlayerOptimized($nickname, $region = 'EU')
    {
        $cacheKey = "search_player_{$nickname}_{$region}";
        
        return Cache::remember($cacheKey, 300, function () use ($nickname, $region) {
            try {
                // 1. Rechercher le joueur
                $player = $this->getPlayerByNickname($nickname);
                
                if (!$player || !isset($player['games'][$this->gameId])) {
                    throw new \Exception("Ce joueur n'a pas de profil CS2");
                }
                
                // 2. Récupérer les données complètes
                $fullData = $this->getPlayerWithStats($player['player_id']);
                
                if (!$fullData) {
                    throw new \Exception("Impossible de récupérer les données du joueur");
                }
                
                // 3. Essayer de récupérer sa position dans le classement
                $position = 'N/A';
                try {
                    $ranking = $this->getPlayerRanking($player['player_id'], $region);
                    $position = $ranking['position'] ?? 'N/A';
                } catch (\Exception $e) {
                    Log::info("Pas de ranking pour {$nickname}: " . $e->getMessage());
                }
                
                return array_merge($fullData, [
                    'player_id' => $player['player_id'],
                    'position' => $position
                ]);
                
            } catch (\Exception $e) {
                Log::error("Erreur recherche optimisée pour {$nickname}: " . $e->getMessage());
                throw $e;
            }
        });
    }
}
