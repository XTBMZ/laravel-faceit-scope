<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        if (!$response->successful()) {
            Log::error('FACEIT API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            throw new \Exception('Erreur API FACEIT: ' . $response->status());
        }

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
}