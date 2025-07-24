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

        return $response->json();
    }

    // ===============================================
    // MÉTHODES PLAYERS (gardées pour d'autres pages)
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
    // MÉTHODES MATCHES (gardées pour d'autres pages)
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
    // MÉTHODES UTILITAIRES MATCHES (gardées)
    // ===============================================

    public function extractMatchId($input)
    {
        if (!$input) {
            throw new \InvalidArgumentException('Input vide pour l\'extraction de l\'ID de match');
        }

        $input = trim($input);

        if ($this->isValidMatchId($input)) {
            return $input;
        }

        if (strpos($input, 'faceit.com') !== false) {
            $extractedId = $this->extractIdFromFaceitUrl($input);
            if ($extractedId) {
                return $extractedId;
            }
        }

        $cleanedInput = $this->cleanMatchInput($input);
        if ($this->isValidMatchId($cleanedInput)) {
            return $cleanedInput;
        }

        throw new \InvalidArgumentException("Format d'ID ou d'URL de match non reconnu: {$input}");
    }

    private function extractIdFromFaceitUrl($url)
    {
        $patterns = [
            '/\/room\/([a-f0-9\-]+)/i',
            '/\/match\/([a-f0-9\-]+)/i',
            '/[\?&]matchId=([a-f0-9\-]+)/i',
            '/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i',
            '/(\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private function cleanMatchInput($input)
    {
        $input = trim($input);
        $input = explode('?', $input)[0];
        $input = explode('#', $input)[0];
        $input = rtrim($input, '/');
        
        $suffixes = ['/scoreboard', '/stats', '/overview'];
        foreach ($suffixes as $suffix) {
            if (str_ends_with($input, $suffix)) {
                $input = substr($input, 0, -strlen($suffix));
            }
        }
        
        return $input;
    }

    public function isValidMatchId($matchId)
    {
        if (!$matchId) return false;
        
        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }
        
        if (preg_match('/^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }
        
        if (preg_match('/^[a-f0-9]{24}$/i', $matchId)) {
            return true;
        }
        
        return false;
    }

    public function searchMatch($input)
    {
        try {
            $matchId = $this->extractMatchId($input);
            $match = $this->getMatch($matchId);
            
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
    // AUTRES MÉTHODES (gardées pour d'autres pages)
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
}