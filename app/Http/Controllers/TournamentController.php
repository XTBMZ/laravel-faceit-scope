<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TournamentController extends Controller
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
     * Affiche la page des tournois
     */
    public function index()
    {
        return view('tournaments.index');
    }
    
    /**
     * Récupère les championnats par type
     */
    public function getChampionships(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type', 'all');
            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 10), 10); // Max 10 selon l'API
            
            // Construire l'URL de l'API FACEIT
            $url = self::FACEIT_URL . "championships?game=" . self::GAME_ID . "&offset={$offset}&limit={$limit}";
            
            // Ajouter le type si différent de 'all'
            if ($type !== 'all' && in_array($type, ['upcoming', 'ongoing', 'past'])) {
                $url .= "&type={$type}";
            }
            
            Log::info("Requête API FACEIT Championships", ['url' => $url, 'type' => $type, 'offset' => $offset]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            $championships = $data['items'] ?? [];
            
            // Enrichir les données des championnats
            $enrichedChampionships = array_map([$this, 'enrichChampionshipData'], $championships);
            
            // Filtrer pour les types spéciaux
            if ($type === 'featured') {
                $enrichedChampionships = array_filter($enrichedChampionships, function($championship) {
                    return $championship['featured'] || $championship['isFeatured'];
                });
            }
            
            return response()->json([
                'success' => true,
                'championships' => array_values($enrichedChampionships),
                'hasMore' => count($championships) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($championships))
            ]);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championships", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des championnats'
            ], 500);
        }
    }
    
    /**
     * Récupère les détails d'un championnat
     */
    public function getChampionshipDetails(Request $request, string $championshipId): JsonResponse
    {
        try {
            $expanded = $request->get('expanded', ['organizer', 'game']);
            
            $url = self::FACEIT_URL . "championships/{$championshipId}";
            if (!empty($expanded)) {
                $url .= "?expanded=" . implode(',', $expanded);
            }
            
            Log::info("Requête API FACEIT Championship Details", ['url' => $url, 'id' => $championshipId]);
            
            $response = $this->client->get($url);
            $championship = json_decode($response->getBody(), true);
            
            $enriched = $this->enrichChampionshipData($championship);
            
            return response()->json([
                'success' => true,
                'championship' => $enriched
            ]);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Details", ['error' => $e->getMessage(), 'id' => $championshipId]);
            return response()->json([
                'success' => false,
                'error' => 'Championnat non trouvé'
            ], 404);
        }
    }
    
    /**
     * Récupère les matches d'un championnat
     */
    public function getChampionshipMatches(Request $request, string $championshipId): JsonResponse
    {
        try {
            $type = $request->get('type', 'all');
            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 20), 100); // Max 100 selon l'API
            
            $url = self::FACEIT_URL . "championships/{$championshipId}/matches?offset={$offset}&limit={$limit}";
            
            if ($type !== 'all' && in_array($type, ['upcoming', 'ongoing', 'past'])) {
                $url .= "&type={$type}";
            }
            
            Log::info("Requête API FACEIT Championship Matches", ['url' => $url]);
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return response()->json([
                'success' => true,
                'matches' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit,
                'start' => $data['start'] ?? $offset,
                'end' => $data['end'] ?? ($offset + count($data['items'] ?? []))
            ]);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Matches", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des matches'
            ], 500);
        }
    }
    
    /**
     * Récupère les résultats d'un championnat
     */
    public function getChampionshipResults(Request $request, string $championshipId): JsonResponse
    {
        try {
            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 20), 100);
            
            $url = self::FACEIT_URL . "championships/{$championshipId}/results?offset={$offset}&limit={$limit}";
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return response()->json([
                'success' => true,
                'results' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit
            ]);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Results", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des résultats'
            ], 500);
        }
    }
    
    /**
     * Récupère les inscriptions d'un championnat
     */
    public function getChampionshipSubscriptions(Request $request, string $championshipId): JsonResponse
    {
        try {
            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 10), 10);
            
            $url = self::FACEIT_URL . "championships/{$championshipId}/subscriptions?offset={$offset}&limit={$limit}";
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            return response()->json([
                'success' => true,
                'subscriptions' => $data['items'] ?? [],
                'hasMore' => count($data['items'] ?? []) === $limit
            ]);
            
        } catch (RequestException $e) {
            Log::error("Erreur API FACEIT Championship Subscriptions", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des inscriptions'
            ], 500);
        }
    }
    
    /**
     * Recherche de championnats
     */
    public function searchChampionships(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query', '');
            $type = $request->get('type', 'all');
            
            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Query de recherche requis'
                ], 400);
            }
            
            // Rechercher dans plusieurs pages
            $allResults = [];
            for ($page = 0; $page < 5; $page++) {
                $offset = $page * 10;
                $result = $this->getChampionshipsData($type, $offset, 10);
                
                if (empty($result['championships'])) {
                    break;
                }
                
                $allResults = array_merge($allResults, $result['championships']);
                
                if (!$result['hasMore']) {
                    break;
                }
            }
            
            // Filtrer par nom
            $filtered = array_filter($allResults, function($championship) use ($query) {
                return stripos($championship['name'] ?? '', $query) !== false ||
                       stripos($championship['description'] ?? '', $query) !== false;
            });
            
            return response()->json([
                'success' => true,
                'championships' => array_values($filtered)
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche championnats", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ], 500);
        }
    }
    
    /**
     * Récupère les statistiques globales des championnats
     */
    public function getGlobalStats(): JsonResponse
    {
        try {
            // Utiliser le cache pour éviter trop de requêtes
            $stats = Cache::remember('championships_global_stats', 300, function () {
                $ongoingStats = $this->getChampionshipsData('ongoing', 0, 10);
                $upcomingStats = $this->getChampionshipsData('upcoming', 0, 10);
                
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
            });
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error("Erreur stats globales", ['error' => $e->getMessage()]);
            return response()->json([
                'ongoing' => 0,
                'upcoming' => 0,
                'totalPrizePool' => 0,
                'totalPlayers' => 0
            ]);
        }
    }
    
    /**
     * Méthode helper pour récupérer les données de championnats
     */
    private function getChampionshipsData(string $type, int $offset, int $limit): array
    {
        try {
            $url = self::FACEIT_URL . "championships?game=" . self::GAME_ID . "&offset={$offset}&limit={$limit}";
            
            if ($type !== 'all' && in_array($type, ['upcoming', 'ongoing', 'past'])) {
                $url .= "&type={$type}";
            }
            
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            
            $championships = $data['items'] ?? [];
            $enrichedChampionships = array_map([$this, 'enrichChampionshipData'], $championships);
            
            return [
                'championships' => $enrichedChampionships,
                'hasMore' => count($championships) === $limit
            ];
            
        } catch (RequestException $e) {
            Log::error("Erreur getChampionshipsData", ['error' => $e->getMessage()]);
            return [
                'championships' => [],
                'hasMore' => false
            ];
        }
    }
    
    /**
     * Enrichit les données d'un championnat
     */
    private function enrichChampionshipData(array $championship): array
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
        
        $finalStatus = strtolower($status);
        
        // Déterminer le vrai statut basé sur les timestamps
        if ($startTime > 0) {
            if ($now < $startTime) {
                $finalStatus = $subscriptionEnd > $now ? 'registration' : 'upcoming';
            } else {
                $finalStatus = 'ongoing';
            }
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
            'OC' => ['name' => 'Océanie', 'icon' => 'fas fa-globe', 'color' => 'purple-400']
        ];
        
        return $regionMap[$region] ?? ['name' => 'Global', 'icon' => 'fas fa-globe', 'color' => 'gray-400'];
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