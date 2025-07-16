<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChampionshipService
{
    private const FACEIT_TOKEN = "9bcea3f9-2144-495e-be16-02d4eb1a811c";
    private const FACEIT_URL = "https://open.faceit.com/data/v4/";
    private const GAME_ID = "cs2";
    
    private $client;
    
    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'headers' => [
                'Authorization' => 'Bearer ' . self::FACEIT_TOKEN,
                'Content-Type' => 'application/json'
            ]
        ]);
    }
    
    /**
     * Récupère tous les championnats d'un jeu
     */
    public function getChampionships(string $type = 'all', int $offset = 0, int $limit = 10): array
    {
        try {
            $url = self::FACEIT_URL . "championships?game=" . self::GAME_ID . "&offset={$offset}&limit={$limit}";
            
            // Ajouter le type si différent de 'all'
            if ($type !== 'all' && in_array($type, ['upcoming', 'ongoing', 'past'])) {
                $url .= "&type={$type}";
            }
            
            Log::info("Requête API FACEIT Championships", [
                'url' => $url,
                'type' => $type,
                'offset' => $offset,
                'limit' => $limit
            ]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            $championships = $data['items'] ?? [];
            
            // Enrichir les données des championnats
            $enrichedChampionships = array_map([$this, 'enrichChampionshipData'], $championships);
            
            return [
                'championships' => $enrichedChampionships,
                'hasMore' => count($championships) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($championships))
            ];
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championships", [
                'error' => $e->getMessage(),
                'type' => $type,
                'offset' => $offset
            ]);
            
            return [
                'championships' => [],
                'hasMore' => false,
                'start' => $offset,
                'end' => $offset
            ];
        }
    }
    
    /**
     * Récupère les détails d'un championnat
     */
    public function getChampionshipDetails(string $championshipId, array $expanded = ['organizer', 'game']): ?array
    {
        try {
            $url = self::FACEIT_URL . "championships/{$championshipId}";
            if (!empty($expanded)) {
                $url .= "?expanded=" . implode(',', $expanded);
            }
            
            Log::info("Requête API FACEIT Championship Details", [
                'url' => $url,
                'championship_id' => $championshipId,
                'expanded' => $expanded
            ]);
            
            $response = $this->client->get($url);
            $championship = json_decode($response->getBody(), true);
            
            return $this->enrichChampionshipData($championship);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Details", [
                'error' => $e->getMessage(),
                'championship_id' => $championshipId
            ]);
            
            return null;
        }
    }
    
    /**
     * Récupère tous les matches d'un championnat
     */
    public function getChampionshipMatches(string $championshipId, string $type = 'all', int $offset = 0, int $limit = 20): array
    {
        try {
            $url = self::FACEIT_URL . "championships/{$championshipId}/matches?offset={$offset}&limit={$limit}";
            
            if ($type !== 'all' && in_array($type, ['upcoming', 'ongoing', 'past'])) {
                $url .= "&type={$type}";
            }
            
            Log::info("Requête API FACEIT Championship Matches", [
                'url' => $url,
                'championship_id' => $championshipId
            ]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return [
                'matches' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($data['items'] ?? []))
            ];
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Matches", [
                'error' => $e->getMessage(),
                'championship_id' => $championshipId
            ]);
            
            return [
                'matches' => [],
                'hasMore' => false,
                'start' => $offset,
                'end' => $offset
            ];
        }
    }
    
    /**
     * Récupère tous les résultats d'un championnat
     */
    public function getChampionshipResults(string $championshipId, int $offset = 0, int $limit = 20): array
    {
        try {
            $url = self::FACEIT_URL . "championships/{$championshipId}/results?offset={$offset}&limit={$limit}";
            
            Log::info("Requête API FACEIT Championship Results", [
                'url' => $url,
                'championship_id' => $championshipId
            ]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return [
                'results' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($data['items'] ?? []))
            ];
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Results", [
                'error' => $e->getMessage(),
                'championship_id' => $championshipId
            ]);
            
            return [
                'results' => [],
                'hasMore' => false,
                'start' => $offset,
                'end' => $offset
            ];
        }
    }
    
    /**
     * Récupère toutes les inscriptions d'un championnat
     */
    public function getChampionshipSubscriptions(string $championshipId, int $offset = 0, int $limit = 10): array
    {
        try {
            $url = self::FACEIT_URL . "championships/{$championshipId}/subscriptions?offset={$offset}&limit={$limit}";
            
            Log::info("Requête API FACEIT Championship Subscriptions", [
                'url' => $url,
                'championship_id' => $championshipId
            ]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return [
                'subscriptions' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($data['items'] ?? []))
            ];
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Subscriptions", [
                'error' => $e->getMessage(),
                'championship_id' => $championshipId
            ]);
            
            return [
                'subscriptions' => [],
                'hasMore' => false,
                'start' => $offset,
                'end' => $offset
            ];
        }
    }
    
    /**
     * Recherche de championnats
     */
    public function searchChampionships(string $query, string $type = 'all'): array
    {
        try {
            $allResults = [];
            
            // Rechercher dans plusieurs pages
            for ($page = 0; $page < 5; $page++) {
                $offset = $page * 10;
                $result = $this->getChampionships($type, $offset, 10);
                
                if (empty($result['championships'])) {
                    break;
                }
                
                $allResults = array_merge($allResults, $result['championships']);
                
                if (!$result['hasMore']) {
                    break;
                }
            }
            
            // Filtrer par nom/description
            $filtered = array_filter($allResults, function($championship) use ($query) {
                $name = $championship['name'] ?? '';
                $description = $championship['description'] ?? '';
                
                return stripos($name, $query) !== false || 
                       stripos($description, $query) !== false;
            });
            
            return array_values($filtered);
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche championnats", [
                'error' => $e->getMessage(),
                'query' => $query
            ]);
            
            return [];
        }
    }
    
    /**
     * Récupère les statistiques globales des championnats
     */
    public function getGlobalStats(): array
    {
        return Cache::remember('championships_global_stats', 300, function () {
            try {
                $ongoingStats = $this->getChampionships('ongoing', 0, 10);
                $upcomingStats = $this->getChampionships('upcoming', 0, 10);
                
                $totalPrizePool = 0;
                $totalPlayers = 0;
                
                foreach (array_merge($ongoingStats['championships'], $upcomingStats['championships']) as $championship) {
                    $totalPrizePool += $championship['total_prizes'] ?? 0;
                    $totalPlayers += $championship['current_subscriptions'] ?? 0;
                }
                
                return [
                    'ongoing' => count($ongoingStats['championships']),
                    'upcoming' => count($upcomingStats['championships']),
                    'totalPrizePool' => $totalPrizePool,
                    'totalPlayers' => $totalPlayers
                ];
                
            } catch (\Exception $e) {
                Log::error("Erreur stats globales", ['error' => $e->getMessage()]);
                
                return [
                    'ongoing' => 0,
                    'upcoming' => 0,
                    'totalPrizePool' => 0,
                    'totalPlayers' => 0
                ];
            }
        });
    }
    
    /**
     * Enrichit les données d'un championnat avec des champs calculés
     */
    public function enrichChampionshipData(array $championship): array
    {
        $enriched = $championship;
        
        // Ajouter des champs calculés
        $enriched['prizeMoney'] = $championship['total_prizes'] ?? 0;
        $enriched['participants'] = $championship['current_subscriptions'] ?? 0;
        $enriched['maxParticipants'] = $championship['slots'] ?? 'Illimité';
        $enriched['statusInfo'] = $this->getEnhancedStatus($championship);
        $enriched['timeInfo'] = $this->getTimeInfo($championship);
        $enriched['regionFlag'] = $this->getRegionInfo($championship['region'] ?? 'EU');
        $enriched['competitionLevel'] = $this->getCompetitionLevel($championship);
        $enriched['isFeatured'] = $this->isFeaturedChampionship($championship);
        $enriched['cleanImageUrl'] = $this->cleanImageUrl($championship['cover_image'] ?? $championship['background_image'] ?? null);
        $enriched['cleanFaceitUrl'] = $this->cleanFaceitUrl($championship['faceit_url'] ?? null);
        
        // Ajouter les données de l'organisateur si disponibles
        if (isset($championship['organizer_data'])) {
            $enriched['organizer_name'] = $championship['organizer_data']['name'] ?? null;
            $enriched['organizer_avatar'] = $championship['organizer_data']['avatar'] ?? null;
            $enriched['organizer_type'] = $championship['organizer_data']['type'] ?? null;
        }
        
        // Ajouter les données du jeu si disponibles
        if (isset($championship['game_data'])) {
            $enriched['game_name'] = $championship['game_data']['long_label'] ?? null;
            $enriched['game_short_name'] = $championship['game_data']['short_label'] ?? null;
        }
        
        return $enriched;
    }
    
    /**
     * Obtient le statut enrichi d'un championnat
     */
    private function getEnhancedStatus(array $championship): array
    {
        $status = $championship['status'] ?? 'unknown';
        $now = time();
        $startTime = $championship['championship_start'] ?? 0;
        $subscriptionEnd = $championship['subscription_end'] ?? 0;
        $subscriptionStart = $championship['subscription_start'] ?? 0;
        
        $finalStatus = strtolower($status);
        
        // Déterminer le vrai statut basé sur les timestamps
        if ($startTime > 0) {
            if ($now < $subscriptionStart && $subscriptionStart > 0) {
                $finalStatus = 'upcoming';
            } elseif ($now >= $subscriptionStart && $now < $subscriptionEnd && $subscriptionEnd > 0) {
                $finalStatus = 'registration';
            } elseif ($now < $startTime) {
                $finalStatus = 'upcoming';
            } else {
                $finalStatus = 'ongoing';
            }
        }
        
        // Mapper les status API FACEIT
        if ($status === 'FINISHED' || $status === 'CANCELLED') {
            $finalStatus = 'past';
        } elseif ($status === 'ONGOING') {
            $finalStatus = 'ongoing';
        } elseif ($status === 'VOTING') {
            $finalStatus = 'upcoming';
        }
        
        $statusMap = [
            'ongoing' => [
                'text' => 'EN COURS',
                'icon' => 'fas fa-play',
                'textColor' => 'text-white',
                'bgFrom' => '#ef4444',
                'bgTo' => '#dc2626',
                'borderColor' => 'rgba(239, 68, 68, 0.5)',
                'shadowColor' => 'rgba(239, 68, 68, 0.3)'
            ],
            'upcoming' => [
                'text' => 'À VENIR',
                'icon' => 'fas fa-calendar-plus',
                'textColor' => 'text-white',
                'bgFrom' => '#3b82f6',
                'bgTo' => '#2563eb',
                'borderColor' => 'rgba(59, 130, 246, 0.5)',
                'shadowColor' => 'rgba(59, 130, 246, 0.3)'
            ],
            'registration' => [
                'text' => 'INSCRIPTIONS',
                'icon' => 'fas fa-user-plus',
                'textColor' => 'text-white',
                'bgFrom' => '#10b981',
                'bgTo' => '#059669',
                'borderColor' => 'rgba(16, 185, 129, 0.5)',
                'shadowColor' => 'rgba(16, 185, 129, 0.3)'
            ],
            'past' => [
                'text' => 'TERMINÉ',
                'icon' => 'fas fa-flag-checkered',
                'textColor' => 'text-white',
                'bgFrom' => '#6b7280',
                'bgTo' => '#4b5563',
                'borderColor' => 'rgba(107, 114, 128, 0.5)',
                'shadowColor' => 'rgba(107, 114, 128, 0.3)'
            ]
        ];
        
        return $statusMap[$finalStatus] ?? $statusMap['upcoming'];
    }
    
    /**
     * Obtient les informations temporelles d'un championnat
     */
    private function getTimeInfo(array $championship): array
    {
        $start = $championship['championship_start'] ?? $championship['subscription_start'] ?? 0;
        
        if (!$start) {
            return ['display' => 'Date à confirmer', 'raw' => null];
        }
        
        $startDate = $start;
        $now = time();
        $diffDays = ceil(($startDate - $now) / (60 * 60 * 24));
        
        if ($diffDays > 0) {
            $display = $diffDays === 1 ? 'Demain' : "Dans {$diffDays} jours";
        } elseif ($diffDays === 0) {
            $display = 'Aujourd\'hui';
        } else {
            $display = date('j M', $startDate);
        }
        
        return [
            'display' => $display,
            'raw' => $startDate
        ];
    }
    
    /**
     * Obtient les informations de région
     */
    private function getRegionInfo(string $region): array
    {
        $regionMap = [
            'EU' => ['name' => 'Europe', 'icon' => 'fas fa-globe-europe', 'color' => 'blue-400'],
            'NA' => ['name' => 'Amérique du Nord', 'icon' => 'fas fa-globe-americas', 'color' => 'green-400'],
            'SA' => ['name' => 'Amérique du Sud', 'icon' => 'fas fa-globe-americas', 'color' => 'yellow-400'],
            'AS' => ['name' => 'Asie', 'icon' => 'fas fa-globe-asia', 'color' => 'red-400'],
            'AF' => ['name' => 'Afrique', 'icon' => 'fas fa-globe-africa', 'color' => 'orange-400'],
            'OC' => ['name' => 'Océanie', 'icon' => 'fas fa-globe', 'color' => 'purple-400'],
            'GLOBAL' => ['name' => 'Global', 'icon' => 'fas fa-globe', 'color' => 'gray-400']
        ];
        
        return $regionMap[strtoupper($region)] ?? $regionMap['GLOBAL'];
    }
    
    /**
     * Calcule le niveau de compétition
     */
    private function getCompetitionLevel(array $championship): int
    {
        $prizePool = $championship['total_prizes'] ?? 0;
        $participants = $championship['current_subscriptions'] ?? 0;
        $featured = $championship['featured'] ?? false;
        
        if ($featured || $prizePool >= 100000 || $participants >= 1000) return 5;
        if ($prizePool >= 50000 || $participants >= 500) return 4;
        if ($prizePool >= 10000 || $participants >= 200) return 3;
        if ($prizePool >= 1000 || $participants >= 50) return 2;
        return 1;
    }
    
    /**
     * Détermine si c'est un championnat premium
     */
    private function isFeaturedChampionship(array $championship): bool
    {
        return $championship['featured'] ?? false ||
               ($championship['total_prizes'] ?? 0) > 10000 ||
               ($championship['current_subscriptions'] ?? 0) > 500;
    }
    
    /**
     * Nettoie l'URL d'une image
     */
    private function cleanImageUrl(?string $url): ?string
    {
        if (!$url) return null;
        
        return str_replace(
            ['{lang}', '{language}', '{locale}', '{region}'],
            ['en', 'en', 'en', 'eu'],
            $url
        );
    }
    
    /**
     * Nettoie l'URL FACEIT
     */
    private function cleanFaceitUrl(?string $url): string
    {
        if (!$url) return '#';
        return str_replace('{lang}', 'fr', $url);
    }
}