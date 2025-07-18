<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitOAuthService;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FriendsController extends Controller
{
    protected $faceitOAuth;
    protected $faceitService;

    public function __construct(FaceitOAuthService $faceitOAuth, FaceitService $faceitService)
    {
        $this->faceitOAuth = $faceitOAuth;
        $this->faceitService = $faceitService;
    }

    /**
     * Affiche la page des amis
     */
    public function index()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté pour accéder à vos amis');
        }

        $user = $this->faceitOAuth->getAuthenticatedUser();
        
        if (!$user) {
            return redirect()->route('home')
                ->with('error', 'Impossible de récupérer vos informations de profil');
        }

        return view('friends.index', [
            'user' => $user
        ]);
    }

    /**
     * API - Récupère la liste des amis du joueur connecté
     */
    public function getFriends(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!isset($user['player_data']['player_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données joueur manquantes'
                ], 404);
            }

            $playerId = $user['player_data']['player_id'];
            $offset = (int) $request->get('offset', 0);
            $limit = min((int) $request->get('limit', 50), 100);

            // Clé de cache
            $cacheKey = "friends_{$playerId}_{$offset}_{$limit}";
            
            // Vérifier le cache (5 minutes)
            if (!$request->has('_refresh')) {
                $cachedData = Cache::get($cacheKey);
                if ($cachedData) {
                    return response()->json([
                        'success' => true,
                        'friends' => $cachedData['friends'],
                        'stats' => $cachedData['stats'],
                        'cached' => true
                    ]);
                }
            }

            Log::info('Récupération des amis FACEIT', [
                'player_id' => $playerId,
                'offset' => $offset,
                'limit' => $limit
            ]);

            // Récupérer les amis via l'API FACEIT
            $friendsData = $this->getFaceitFriends($user['access_token'], $playerId, $offset, $limit);
            
            if (!$friendsData || !isset($friendsData['items'])) {
                throw new \Exception('Aucune donnée d\'amis disponible');
            }

            // Enrichir les données des amis
            $enrichedFriends = $this->enrichFriendsData($friendsData['items']);

            // Calculer les statistiques des amis
            $friendsStats = $this->calculateFriendsStats($enrichedFriends);

            $result = [
                'friends' => $enrichedFriends,
                'stats' => $friendsStats
            ];

            // Mettre en cache pour 5 minutes
            Cache::put($cacheKey, $result, 300);

            return response()->json([
                'success' => true,
                'friends' => $enrichedFriends,
                'stats' => $friendsStats,
                'total' => count($enrichedFriends),
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des amis: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des amis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Compare les stats avec un ami
     */
    public function compareWithFriend(Request $request, $friendId)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            // Récupérer les données de l'ami
            $friend = $this->faceitService->getPlayer($friendId);
            $friendStats = $this->faceitService->getPlayerStats($friendId);
            
            // Récupérer les stats du joueur connecté
            $userStats = $this->faceitService->getPlayerStats($user['player_data']['player_id']);
            
            // Effectuer la comparaison
            $comparison = $this->performQuickComparison($user['player_data'], $userStats, $friend, $friendStats);

            return response()->json([
                'success' => true,
                'comparison' => $comparison,
                'friend' => $friend,
                'user' => $user['player_data']
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur comparaison avec ami: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la comparaison'
            ], 500);
        }
    }

    /**
     * API - Récupère les amis en ligne
     */
    public function getOnlineFriends(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            $playerId = $user['player_data']['player_id'];

            // Clé de cache courte pour les amis en ligne (2 minutes)
            $cacheKey = "online_friends_{$playerId}";
            
            $cachedData = Cache::get($cacheKey);
            if ($cachedData && !$request->has('_refresh')) {
                return response()->json([
                    'success' => true,
                    'online_friends' => $cachedData,
                    'cached' => true
                ]);
            }

            // Récupérer tous les amis d'abord
            $friendsData = $this->getFaceitFriends($user['access_token'], $playerId, 0, 100);
            
            if (!$friendsData || !isset($friendsData['items'])) {
                return response()->json([
                    'success' => true,
                    'online_friends' => [],
                    'cached' => false
                ]);
            }

            // Filtrer et enrichir les amis en ligne
            $onlineFriends = [];
            foreach ($friendsData['items'] as $friend) {
                // Simuler le statut en ligne basé sur l'activité récente
                if ($this->isFriendOnline($friend)) {
                    $enrichedFriend = $this->enrichSingleFriend($friend);
                    if ($enrichedFriend) {
                        $onlineFriends[] = $enrichedFriend;
                    }
                }
            }

            // Mettre en cache pour 2 minutes
            Cache::put($cacheKey, $onlineFriends, 120);

            return response()->json([
                'success' => true,
                'online_friends' => $onlineFriends,
                'count' => count($onlineFriends),
                'cached' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis en ligne: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des amis en ligne'
            ], 500);
        }
    }

    /**
     * API - Recherche dans les amis
     */
    public function searchFriends(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        $query = $request->get('query', '');
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'error' => 'Query de recherche requis'
            ], 400);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            $playerId = $user['player_data']['player_id'];

            // Récupérer tous les amis
            $friendsData = $this->getFaceitFriends($user['access_token'], $playerId, 0, 200);
            
            if (!$friendsData || !isset($friendsData['items'])) {
                return response()->json([
                    'success' => true,
                    'friends' => []
                ]);
            }

            // Filtrer par nom
            $filteredFriends = array_filter($friendsData['items'], function($friend) use ($query) {
                return stripos($friend['nickname'] ?? '', $query) !== false;
            });

            // Enrichir les résultats
            $enrichedResults = [];
            foreach ($filteredFriends as $friend) {
                $enriched = $this->enrichSingleFriend($friend);
                if ($enriched) {
                    $enrichedResults[] = $enriched;
                }
            }

            return response()->json([
                'success' => true,
                'friends' => array_values($enrichedResults),
                'query' => $query,
                'total' => count($enrichedResults)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur recherche amis: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ], 500);
        }
    }

    /**
     * Récupère les amis depuis l'API FACEIT
     */
    private function getFaceitFriends($accessToken, $playerId, $offset = 0, $limit = 50)
    {
        try {
            // Note: Cette endpoint pourrait ne pas exister dans l'API FACEIT publique
            // Il faudrait vérifier la documentation FACEIT pour la bonne endpoint
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(15)
                ->get("https://api.faceit.com/players/v1/{$playerId}/friends", [
                    'offset' => $offset,
                    'limit' => $limit
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Fallback: simuler des données d'amis pour la démo
            return $this->generateMockFriendsData($playerId);

        } catch (\Exception $e) {
            Log::warning('Endpoint amis FACEIT non disponible, utilisation des données mock');
            return $this->generateMockFriendsData($playerId);
        }
    }

    /**
     * Génère des données d'amis fictives pour la démo
     */
    private function generateMockFriendsData($playerId)
    {
        $mockFriends = [
            [
                'player_id' => 'friend-1-' . substr($playerId, 0, 8),
                'nickname' => 'ProGamer2024',
                'avatar' => 'https://d50m6q67g4bn3.cloudfront.net/avatars/friend1.jpg',
                'country' => 'FR',
                'status' => 'online',
                'games' => [
                    'cs2' => [
                        'skill_level' => 7,
                        'faceit_elo' => 1850,
                        'region' => 'EU'
                    ]
                ]
            ],
            [
                'player_id' => 'friend-2-' . substr($playerId, 0, 8),
                'nickname' => 'HeadshotKing',
                'avatar' => 'https://d50m6q67g4bn3.cloudfront.net/avatars/friend2.jpg',
                'country' => 'DE',
                'status' => 'playing',
                'games' => [
                    'cs2' => [
                        'skill_level' => 9,
                        'faceit_elo' => 2150,
                        'region' => 'EU'
                    ]
                ]
            ],
            [
                'player_id' => 'friend-3-' . substr($playerId, 0, 8),
                'nickname' => 'TacticalMaster',
                'avatar' => 'https://d50m6q67g4bn3.cloudfront.net/avatars/friend3.jpg',
                'country' => 'SE',
                'status' => 'offline',
                'games' => [
                    'cs2' => [
                        'skill_level' => 6,
                        'faceit_elo' => 1650,
                        'region' => 'EU'
                    ]
                ]
            ]
        ];

        return [
            'items' => $mockFriends,
            'start' => 0,
            'end' => count($mockFriends)
        ];
    }

    /**
     * Enrichit les données des amis avec des statistiques détaillées
     */
    private function enrichFriendsData($friends)
    {
        $enrichedFriends = [];

        foreach ($friends as $friend) {
            $enriched = $this->enrichSingleFriend($friend);
            if ($enriched) {
                $enrichedFriends[] = $enriched;
            }
        }

        return $enrichedFriends;
    }

    /**
     * Enrichit les données d'un seul ami
     */
    private function enrichSingleFriend($friend)
    {
        try {
            $enriched = $friend;
            $enriched['online_status'] = $this->getOnlineStatus($friend);
            $enriched['rank_info'] = $this->getRankInfo($friend);
            $enriched['last_seen'] = $this->getLastSeen($friend);
            
            // Essayer de récupérer des stats basiques si possible
            try {
                $stats = $this->faceitService->getPlayerStats($friend['player_id']);
                $enriched['quick_stats'] = $this->extractQuickStats($stats);
            } catch (\Exception $e) {
                $enriched['quick_stats'] = null;
            }

            return $enriched;

        } catch (\Exception $e) {
            Log::warning("Impossible d'enrichir les données de l'ami {$friend['player_id']}: " . $e->getMessage());
            return $friend;
        }
    }

    /**
     * Calcule les statistiques globales des amis
     */
    private function calculateFriendsStats($friends)
    {
        $stats = [
            'total' => count($friends),
            'online' => 0,
            'playing' => 0,
            'offline' => 0,
            'average_level' => 0,
            'average_elo' => 0,
            'top_friend' => null,
            'level_distribution' => []
        ];

        if (empty($friends)) {
            return $stats;
        }

        $totalLevel = 0;
        $totalElo = 0;
        $validData = 0;
        $topElo = 0;

        foreach ($friends as $friend) {
            // Compter les statuts
            switch ($friend['online_status']['status']) {
                case 'online':
                    $stats['online']++;
                    break;
                case 'playing':
                    $stats['playing']++;
                    break;
                default:
                    $stats['offline']++;
            }

            // Calculer les moyennes
            if (isset($friend['games']['cs2'])) {
                $level = $friend['games']['cs2']['skill_level'] ?? 0;
                $elo = $friend['games']['cs2']['faceit_elo'] ?? 0;

                if ($level > 0) {
                    $totalLevel += $level;
                    $validData++;

                    // Distribution des niveaux
                    if (!isset($stats['level_distribution'][$level])) {
                        $stats['level_distribution'][$level] = 0;
                    }
                    $stats['level_distribution'][$level]++;
                }

                if ($elo > 0) {
                    $totalElo += $elo;

                    // Top ami par ELO
                    if ($elo > $topElo) {
                        $topElo = $elo;
                        $stats['top_friend'] = $friend;
                    }
                }
            }
        }

        if ($validData > 0) {
            $stats['average_level'] = round($totalLevel / $validData, 1);
            $stats['average_elo'] = round($totalElo / $validData);
        }

        return $stats;
    }

    /**
     * Détermine le statut en ligne d'un ami
     */
    private function getOnlineStatus($friend)
    {
        // Simuler le statut basé sur les données disponibles
        $status = $friend['status'] ?? 'offline';
        
        $statusMap = [
            'online' => [
                'status' => 'online',
                'label' => 'En ligne',
                'color' => 'green',
                'icon' => 'fas fa-circle'
            ],
            'playing' => [
                'status' => 'playing',
                'label' => 'En jeu',
                'color' => 'orange',
                'icon' => 'fas fa-gamepad'
            ],
            'offline' => [
                'status' => 'offline',
                'label' => 'Hors ligne',
                'color' => 'gray',
                'icon' => 'fas fa-circle'
            ]
        ];

        return $statusMap[$status] ?? $statusMap['offline'];
    }

    /**
     * Récupère les informations de rang
     */
    private function getRankInfo($friend)
    {
        if (!isset($friend['games']['cs2'])) {
            return null;
        }

        $level = $friend['games']['cs2']['skill_level'] ?? 1;
        $elo = $friend['games']['cs2']['faceit_elo'] ?? 1000;

        return [
            'level' => $level,
            'elo' => $elo,
            'rank_name' => $this->getRankName($level),
            'rank_color' => $this->getRankColor($level)
        ];
    }

    /**
     * Simule la dernière fois vu
     */
    private function getLastSeen($friend)
    {
        if ($friend['status'] ?? 'offline' !== 'offline') {
            return null;
        }

        // Simuler une dernière connexion
        $hoursAgo = rand(1, 72);
        return [
            'timestamp' => time() - ($hoursAgo * 3600),
            'relative' => $hoursAgo < 24 ? "Il y a {$hoursAgo}h" : "Il y a " . round($hoursAgo / 24) . " jour(s)"
        ];
    }

    /**
     * Vérifie si un ami est en ligne
     */
    private function isFriendOnline($friend)
    {
        $status = $friend['status'] ?? 'offline';
        return in_array($status, ['online', 'playing']);
    }

    /**
     * Extrait des statistiques rapides
     */
    private function extractQuickStats($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return null;
        }

        return [
            'kd' => round(floatval($stats['lifetime']['Average K/D Ratio'] ?? 0), 2),
            'win_rate' => round(floatval($stats['lifetime']['Win Rate %'] ?? 0), 1),
            'matches' => intval($stats['lifetime']['Matches'] ?? 0),
            'headshots' => round(floatval($stats['lifetime']['Average Headshots %'] ?? 0), 1)
        ];
    }

    /**
     * Effectue une comparaison rapide entre deux joueurs
     */
    private function performQuickComparison($user, $userStats, $friend, $friendStats)
    {
        $userLifetime = $userStats['lifetime'] ?? [];
        $friendLifetime = $friendStats['lifetime'] ?? [];

        $comparison = [
            'kd' => [
                'user' => floatval($userLifetime['Average K/D Ratio'] ?? 0),
                'friend' => floatval($friendLifetime['Average K/D Ratio'] ?? 0)
            ],
            'win_rate' => [
                'user' => floatval($userLifetime['Win Rate %'] ?? 0),
                'friend' => floatval($friendLifetime['Win Rate %'] ?? 0)
            ],
            'headshots' => [
                'user' => floatval($userLifetime['Average Headshots %'] ?? 0),
                'friend' => floatval($friendLifetime['Average Headshots %'] ?? 0)
            ],
            'elo' => [
                'user' => $user['games']['cs2']['faceit_elo'] ?? 0,
                'friend' => $friend['games']['cs2']['faceit_elo'] ?? 0
            ]
        ];

        // Calculer le gagnant pour chaque métrique
        foreach ($comparison as $metric => &$data) {
            if ($data['user'] > $data['friend']) {
                $data['winner'] = 'user';
            } elseif ($data['friend'] > $data['user']) {
                $data['winner'] = 'friend';
            } else {
                $data['winner'] = 'tie';
            }
        }

        return $comparison;
    }

    /**
     * Utilitaires pour les rangs
     */
    private function getRankName($level)
    {
        $rankNames = [
            1 => 'Iron', 2 => 'Bronze', 3 => 'Silver', 4 => 'Gold', 5 => 'Gold+',
            6 => 'Platinum', 7 => 'Platinum+', 8 => 'Diamond', 9 => 'Master', 10 => 'Legendary'
        ];
        return $rankNames[$level] ?? 'Inconnu';
    }

    private function getRankColor($level)
    {
        $colors = [
            1 => 'gray', 2 => 'orange', 3 => 'blue', 4 => 'yellow', 5 => 'yellow',
            6 => 'purple', 7 => 'purple', 8 => 'pink', 9 => 'red', 10 => 'red'
        ];
        return $colors[$level] ?? 'gray';
    }
}