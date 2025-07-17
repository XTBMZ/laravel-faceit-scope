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
        ])->timeout(15)->get($this->baseUrl . $endpoint, $params);

        if (!$response->successful()) {
            Log::error('FACEIT API Error', [
                'endpoint' => $endpoint,
                'params' => $params,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            throw new \Exception('Erreur API FACEIT: ' . $response->status() . ' - ' . $response->body());
        }

        Log::info('FACEIT API Success', [
            'endpoint' => $endpoint,
            'status' => $response->status(),
            'response_size' => strlen($response->body())
        ]);

        return $response->json();
    }

    // ===============================================
    // MÉTHODES PLAYERS
    // ===============================================

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

    // ===============================================
    // MÉTHODES MATCHES - CORRIGÉES SELON L'API FACEIT
    // ===============================================

    public function getMatch($matchId)
    {
        return $this->makeRequest("matches/{$matchId}");
    }

    public function getMatchStats($matchId)
    {
        return $this->makeRequest("matches/{$matchId}/stats");
    }

    // ===============================================
    // MÉTHODES UTILITAIRES POUR LES MATCHES - AMÉLIORÉES
    // ===============================================

    /**
     * Extrait l'ID d'un match à partir d'une URL ou d'un ID FACEIT
     * Gère les différents formats d'URL et d'ID FACEIT
     */
    public function extractMatchId($input)
    {
        if (!$input) {
            throw new \InvalidArgumentException('Input vide pour l\'extraction de l\'ID de match');
        }

        $input = trim($input);
        Log::info("Extraction de l'ID de match depuis: {$input}");

        // Si c'est déjà un ID de match valide (différents formats possibles)
        if ($this->isValidMatchId($input)) {
            Log::info("ID de match déjà valide: {$input}");
            return $input;
        }

        // Si c'est une URL FACEIT, extraire l'ID
        if (strpos($input, 'faceit.com') !== false || strpos($input, 'www.faceit.com') !== false) {
            $extractedId = $this->extractIdFromFaceitUrl($input);
            if ($extractedId) {
                Log::info("ID extrait de l'URL: {$extractedId}");
                return $extractedId;
            }
        }

        // Dernière tentative : nettoyer et valider
        $cleanedInput = $this->cleanMatchInput($input);
        if ($this->isValidMatchId($cleanedInput)) {
            Log::info("ID nettoyé valide: {$cleanedInput}");
            return $cleanedInput;
        }

        throw new \InvalidArgumentException("Format d'ID ou d'URL de match non reconnu: {$input}");
    }

    /**
     * Extrait l'ID depuis une URL FACEIT
     */
    private function extractIdFromFaceitUrl($url)
    {
        // Patterns pour différents formats d'URL FACEIT
        $patterns = [
            // Format moderne: /room/{id}
            '/\/room\/([a-f0-9\-]+)/i',
            // Format classique: /match/{id}
            '/\/match\/([a-f0-9\-]+)/i',
            // Format avec paramètres: ?matchId={id}
            '/[\?&]matchId=([a-f0-9\-]+)/i',
            // Format général UUID dans l'URL
            '/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i',
            // Format avec préfixe numérique: 1-{uuid}
            '/(\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Nettoie l'input du match
     */
    private function cleanMatchInput($input)
    {
        // Supprimer les espaces
        $input = trim($input);
        
        // Supprimer les paramètres d'URL
        $input = explode('?', $input)[0];
        $input = explode('#', $input)[0];
        
        // Supprimer les slashes de fin
        $input = rtrim($input, '/');
        
        // Supprimer les suffixes communs
        $suffixes = ['/scoreboard', '/stats', '/overview'];
        foreach ($suffixes as $suffix) {
            if (str_ends_with($input, $suffix)) {
                $input = substr($input, 0, -strlen($suffix));
            }
        }
        
        return $input;
    }

    /**
     * Valide si un ID de match est au bon format
     */
    public function isValidMatchId($matchId)
    {
        if (!$matchId) return false;
        
        // Format UUID standard
        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }
        
        // Format avec préfixe numérique (ex: 1-uuid)
        if (preg_match('/^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }
        
        // Format court FACEIT (parfois utilisé)
        if (preg_match('/^[a-f0-9]{24}$/i', $matchId)) {
            return true;
        }
        
        return false;
    }

    /**
     * Recherche optimisée d'un match avec validation
     */
    public function searchMatch($input)
    {
        try {
            $matchId = $this->extractMatchId($input);
            $match = $this->getMatch($matchId);
            
            // Vérifier que le match est valide
            if (!$match || !isset($match['match_id'])) {
                throw new \Exception("Match non trouvé ou invalide");
            }
            
            return [
                'match' => $match,
                'match_id' => $matchId,
                'found' => true
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche match: " . $e->getMessage());
            throw $e;
        }
    }

    // ===============================================
    // MÉTHODES RANKINGS (API OFFICIELLE FACEIT)
    // ===============================================

    /**
     * Récupération du classement global d'un jeu par région (API FACEIT officielle)
     * GET /rankings/games/{game_id}/regions/{region}
     */
    public function getGlobalRanking($region, $country = null, $offset = 0, $limit = 20)
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        try {
            Log::info('API FACEIT Rankings - Global Ranking', [
                'region' => $region,
                'country' => $country,
                'offset' => $offset,
                'limit' => $limit,
                'url' => "rankings/games/{$this->gameId}/regions/{$region}"
            ]);

            $response = $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}", $params);
            
            Log::info('API FACEIT Rankings - Réponse reçue', [
                'items_count' => count($response['items'] ?? []),
                'start' => $response['start'] ?? null,
                'end' => $response['end'] ?? null
            ]);

            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erreur API FACEIT getGlobalRanking', [
                'region' => $region,
                'country' => $country,
                'offset' => $offset,
                'limit' => $limit,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Récupération de la position d'un joueur dans le classement global (API FACEIT officielle)
     * GET /rankings/games/{game_id}/regions/{region}/players/{player_id}
     */
    public function getPlayerRanking($playerId, $region, $country = null, $limit = 20)
    {
        $params = ['limit' => $limit];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        try {
            Log::info('API FACEIT Rankings - Player Ranking', [
                'player_id' => $playerId,
                'region' => $region,
                'country' => $country,
                'limit' => $limit,
                'url' => "rankings/games/{$this->gameId}/regions/{$region}/players/{$playerId}"
            ]);

            $response = $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}/players/{$playerId}", $params);
            
            Log::info('API FACEIT Rankings - Player Ranking réponse', [
                'position' => $response['position'] ?? null,
                'items_count' => count($response['items'] ?? [])
            ]);

            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erreur API FACEIT getPlayerRanking', [
                'playerId' => $playerId,
                'region' => $region,
                'country' => $country,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // ===============================================
    // MÉTHODES SEARCH
    // ===============================================

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

    // ===============================================
    // MÉTHODES GAMES
    // ===============================================

    public function getGameDetails($gameId = null)
    {
        $gameId = $gameId ?? $this->gameId;
        return $this->makeRequest("games/{$gameId}");
    }

    // ===============================================
    // MÉTHODES TEAMS
    // ===============================================

    public function getTeamDetails($teamId)
    {
        return $this->makeRequest("teams/{$teamId}");
    }

    public function getTeamStats($teamId, $gameId = null)
    {
        $gameId = $gameId ?? $this->gameId;
        return $this->makeRequest("teams/{$teamId}/stats/{$gameId}");
    }

    // ===============================================
    // MÉTHODES TOURNAMENTS
    // ===============================================

    public function getTournaments($type = 'upcoming', $offset = 0, $limit = 20)
    {
        $params = [
            'game' => $this->gameId,
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ];
        
        try {
            return $this->makeRequest('tournaments', $params);
        } catch (\Exception $e) {
            Log::error('Erreur API FACEIT getTournaments', [
                'type' => $type,
                'offset' => $offset,
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    // ===============================================
    // MÉTHODES CHAMPIONSHIPS
    // ===============================================

    public function getChampionships($type = 'all', $offset = 0, $limit = 10)
    {
        $params = [
            'game' => $this->gameId,
            'type' => $type,
            'offset' => $offset,
            'limit' => $limit
        ];
        
        try {
            return $this->makeRequest('championships', $params);
        } catch (\Exception $e) {
            Log::error('Erreur API FACEIT getChampionships', [
                'type' => $type,
                'offset' => $offset,
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    // ===============================================
    // MÉTHODES UTILITAIRES
    // ===============================================

    /**
     * Validation et nettoyage des paramètres de classement
     */
    public function validateRankingParams($region, $country = null, $offset = 0, $limit = 20)
    {
        // Validation région
        $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
        if (!in_array(strtoupper($region), $validRegions)) {
            throw new \InvalidArgumentException("Région invalide: {$region}. Régions valides: " . implode(', ', $validRegions));
        }
        
        // Validation pays (ISO 3166-1 alpha-2)
        if ($country && strlen($country) !== 2) {
            throw new \InvalidArgumentException("Code pays invalide: {$country}. Utilisez le format ISO 3166-1 alpha-2 (ex: FR, US, DE)");
        }
        
        // Validation pagination
        if ($offset < 0) $offset = 0;
        if ($limit < 1) $limit = 20;
        if ($limit > 100) $limit = 100; // Limite FACEIT API
        
        return [
            'region' => strtoupper($region),
            'country' => $country ? strtoupper($country) : null,
            'offset' => (int) $offset,
            'limit' => (int) $limit
        ];
    }

    /**
     * Cache intelligent pour les classements
     */
    public function getCachedRanking($region, $country = null, $offset = 0, $limit = 20, $cacheMinutes = 5)
    {
        $cacheKey = "faceit_ranking_{$region}_{$country}_{$offset}_{$limit}";
        
        return Cache::remember($cacheKey, $cacheMinutes * 60, function () use ($region, $country, $offset, $limit) {
            return $this->getGlobalRanking($region, $country, $offset, $limit);
        });
    }

    /**
     * Recherche optimisée d'un joueur avec sa position dans le classement
     */
    public function searchPlayerWithRanking($nickname, $region = 'EU', $country = null)
    {
        try {
            // 1. Rechercher le joueur
            $player = $this->getPlayerByNickname($nickname);
            
            if (!$player || !isset($player['games'][$this->gameId])) {
                throw new \Exception("Ce joueur n'a pas de profil CS2");
            }
            
            // 2. Obtenir sa position dans le classement
            try {
                $playerRanking = $this->getPlayerRanking($player['player_id'], $region, $country, 20);
                $position = $playerRanking['position'] ?? null;
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer la position dans le classement: ' . $e->getMessage());
                $position = null;
            }
            
            // 3. Récupérer les statistiques si possible
            try {
                $stats = $this->getPlayerStats($player['player_id']);
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer les stats: ' . $e->getMessage());
                $stats = null;
            }
            
            return [
                'player' => $player,
                'position' => $position,
                'stats' => $stats,
                'region' => $region
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche joueur avec ranking: " . $e->getMessage());
            throw $e;
        }
    }
}