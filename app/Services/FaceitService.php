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

    public function getPlayerRanking($playerId, $region, $country = null, $limit = 20)
    {
        $params = ['limit' => $limit];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        return $this->makeRequest("rankings/games/{$this->gameId}/regions/{$region}/players/{$playerId}", $params);
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

    public function getLeaderboards($region, $country = null, $offset = 0, $limit = 20)
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];
        
        if ($country) {
            $params['country'] = $country;
        }
        
        return $this->makeRequest("leaderboards/games/{$this->gameId}/regions/{$region}", $params);
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
}