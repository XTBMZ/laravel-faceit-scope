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
     * Page principale des amis
     */
    public function index()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté pour voir vos amis FACEIT');
        }

        return view('friends');
    }

    /**
     * API: Récupère la liste des amis du joueur connecté
     */
    public function getFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $playerId = $user['id'];
            $cacheKey = "friends_list_{$playerId}";
            
            // Cache de 10 minutes pour éviter les appels répétés
            $friendsData = Cache::remember($cacheKey, 600, function () use ($playerId) {
                return $this->fetchFriendsData($playerId);
            });

            return response()->json([
                'success' => true,
                'friends' => $friendsData['friends'],
                'stats' => $friendsData['stats'],
                'cached' => Cache::has($cacheKey)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération amis:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des amis'
            ], 500);
        }
    }

    /**
     * Récupère les données des amis avec traitement par batch
     */
    private function fetchFriendsData($playerId)
    {
        try {
            // 1. Récupérer les données du joueur principal
            $playerData = $this->faceitService->getPlayer($playerId);
            
            if (!$playerData || !isset($playerData['friends_ids'])) {
                return [
                    'friends' => [],
                    'stats' => [
                        'total' => 0,
                        'online' => 0,
                        'cs2_players' => 0,
                        'average_level' => 0
                    ]
                ];
            }

            $friendIds = $playerData['friends_ids'];
            
            if (empty($friendIds)) {
                return [
                    'friends' => [],
                    'stats' => [
                        'total' => 0,
                        'online' => 0,
                        'cs2_players' => 0,
                        'average_level' => 0
                    ]
                ];
            }

            // 2. Traitement par batch pour éviter de surcharger l'API
            $friends = [];
            $batchSize = 5; // Traiter 5 amis à la fois
            $batches = array_chunk($friendIds, $batchSize);
            
            foreach ($batches as $batch) {
                $batchFriends = $this->processFriendsBatch($batch);
                $friends = array_merge($friends, $batchFriends);
                
                // Petite pause entre les batches pour respecter les rate limits
                usleep(100000); // 100ms
            }

            // 3. Trier les amis par statut et niveau
            $friends = $this->sortFriends($friends);

            // 4. Calculer les statistiques
            $stats = $this->calculateFriendsStats($friends);

            return [
                'friends' => $friends,
                'stats' => $stats
            ];

        } catch (\Exception $e) {
            Log::error('Erreur fetchFriendsData:', [
                'error' => $e->getMessage(),
                'player_id' => $playerId
            ]);
            
            return [
                'friends' => [],
                'stats' => [
                    'total' => 0,
                    'online' => 0,
                    'cs2_players' => 0,
                    'average_level' => 0
                ]
            ];
        }
    }

    /**
     * Traite un batch d'amis
     */
    private function processFriendsBatch($friendIds)
    {
        $friends = [];
        
        foreach ($friendIds as $friendId) {
            try {
                // Récupérer les données de l'ami
                $friendData = $this->faceitService->getPlayer($friendId);
                
                if (!$friendData) {
                    continue;
                }

                // Enrichir avec des données calculées
                $enrichedFriend = $this->enrichFriendData($friendData);
                $friends[] = $enrichedFriend;

            } catch (\Exception $e) {
                Log::warning('Erreur récupération ami:', [
                    'friend_id' => $friendId,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return $friends;
    }

    /**
     * Enrichit les données d'un ami
     */
    private function enrichFriendData($friendData)
    {
        $cs2Game = $friendData['games']['cs2'] ?? null;
        $csgoGame = $friendData['games']['csgo'] ?? null;
        $primaryGame = $cs2Game ?: $csgoGame;

        // Calculer le statut d'activité (basé sur la date d'activation)
        $lastSeen = $this->calculateLastSeen($friendData);
        $isOnline = $this->isPlayerOnline($friendData);

        return [
            'player_id' => $friendData['player_id'],
            'nickname' => $friendData['nickname'],
            'avatar' => $friendData['avatar'] ?? null,
            'country' => $friendData['country'] ?? null,
            'verified' => $friendData['verified'] ?? false,
            'membership_type' => $friendData['membership_type'] ?? 'free',
            'faceit_url' => $friendData['faceit_url'] ?? null,
            
            // Données de jeu
            'has_cs2' => $cs2Game !== null,
            'has_csgo' => $csgoGame !== null,
            'level' => $primaryGame['skill_level'] ?? 0,
            'elo' => $primaryGame['faceit_elo'] ?? 0,
            'region' => $primaryGame['region'] ?? 'EU',
            
            // Statut
            'is_online' => $isOnline,
            'last_seen' => $lastSeen,
            'status_text' => $this->getStatusText($isOnline, $lastSeen),
            
            // Métadonnées
            'enriched_at' => time(),
            'game_priority' => $cs2Game ? 'cs2' : ($csgoGame ? 'csgo' : 'none')
        ];
    }

    /**
     * Calcule la dernière fois vu (approximation)
     */
    private function calculateLastSeen($playerData)
    {
        // FACEIT ne fournit pas de données de "dernière connexion" précises
        // On utilise la date d'activation comme approximation
        $activatedAt = $playerData['activated_at'] ?? null;
        
        if ($activatedAt) {
            return strtotime($activatedAt);
        }
        
        return null;
    }

    /**
     * Détermine si un joueur est "en ligne" (heuristique)
     */
    private function isPlayerOnline($playerData)
    {
        // Logique simple : considérer comme "en ligne" si le compte est vérifié
        // et a une activité récente (heuristique basée sur les données disponibles)
        $verified = $playerData['verified'] ?? false;
        $hasPremium = in_array($playerData['membership_type'] ?? 'free', ['premium', 'unlimited']);
        
        // Approximation basée sur le statut du compte
        return $verified && $hasPremium;
    }

    /**
     * Génère le texte de statut
     */
    private function getStatusText($isOnline, $lastSeen)
    {
        if ($isOnline) {
            return 'En ligne';
        }
        
        if ($lastSeen) {
            $now = time();
            $diff = $now - $lastSeen;
            
            if ($diff < 3600) { // 1 heure
                return 'Il y a ' . round($diff / 60) . ' min';
            } elseif ($diff < 86400) { // 24 heures
                return 'Il y a ' . round($diff / 3600) . 'h';
            } elseif ($diff < 604800) { // 7 jours
                return 'Il y a ' . round($diff / 86400) . 'j';
            } else {
                return 'Inactif';
            }
        }
        
        return 'Hors ligne';
    }

    /**
     * Trie la liste des amis
     */
    private function sortFriends($friends)
    {
        usort($friends, function($a, $b) {
            // 1. En ligne d'abord
            if ($a['is_online'] !== $b['is_online']) {
                return $b['is_online'] <=> $a['is_online'];
            }
            
            // 2. Joueurs CS2 d'abord
            if ($a['has_cs2'] !== $b['has_cs2']) {
                return $b['has_cs2'] <=> $a['has_cs2'];
            }
            
            // 3. Par niveau descendant
            if ($a['level'] !== $b['level']) {
                return $b['level'] <=> $a['level'];
            }
            
            // 4. Par ELO descendant
            return $b['elo'] <=> $a['elo'];
        });
        
        return $friends;
    }

    /**
     * Calcule les statistiques des amis
     */
    private function calculateFriendsStats($friends)
    {
        $total = count($friends);
        $online = 0;
        $cs2Players = 0;
        $totalLevel = 0;
        $levelCount = 0;
        
        foreach ($friends as $friend) {
            if ($friend['is_online']) {
                $online++;
            }
            
            if ($friend['has_cs2']) {
                $cs2Players++;
            }
            
            if ($friend['level'] > 0) {
                $totalLevel += $friend['level'];
                $levelCount++;
            }
        }
        
        return [
            'total' => $total,
            'online' => $online,
            'cs2_players' => $cs2Players,
            'average_level' => $levelCount > 0 ? round($totalLevel / $levelCount, 1) : 0,
            'regions' => $this->getRegionDistribution($friends),
            'membership_types' => $this->getMembershipDistribution($friends)
        ];
    }

    /**
     * Distribution par région
     */
    private function getRegionDistribution($friends)
    {
        $regions = [];
        
        foreach ($friends as $friend) {
            $region = $friend['region'];
            $regions[$region] = ($regions[$region] ?? 0) + 1;
        }
        
        arsort($regions);
        return $regions;
    }

    /**
     * Distribution par type de membership
     */
    private function getMembershipDistribution($friends)
    {
        $memberships = [];
        
        foreach ($friends as $friend) {
            $type = $friend['membership_type'];
            $memberships[$type] = ($memberships[$type] ?? 0) + 1;
        }
        
        return $memberships;
    }

    /**
     * API: Compare avec un ami
     */
    public function compareFriend(Request $request, $friendId)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $userNickname = $user['nickname'];
            $friendData = $this->faceitService->getPlayer($friendId);
            $friendNickname = $friendData['nickname'];

            return response()->json([
                'success' => true,
                'redirect_url' => "/comparison?player1=" . urlencode($userNickname) . "&player2=" . urlencode($friendNickname)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la comparaison'
            ], 500);
        }
    }

    /**
     * API: Recherche d'amis
     */
    public function searchFriends(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $query = $request->get('query', '');
            $filter = $request->get('filter', 'all'); // all, online, cs2, high_level
            
            $playerId = $user['id'];
            $cacheKey = "friends_list_{$playerId}";
            
            $friendsData = Cache::get($cacheKey);
            
            if (!$friendsData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données non disponibles, veuillez rafraîchir'
                ], 404);
            }

            $friends = $friendsData['friends'];
            
            // Filtrage par recherche
            if (!empty($query)) {
                $friends = array_filter($friends, function($friend) use ($query) {
                    return stripos($friend['nickname'], $query) !== false ||
                           stripos($friend['country'], $query) !== false;
                });
            }
            
            // Filtrage par type
            switch ($filter) {
                case 'online':
                    $friends = array_filter($friends, function($friend) {
                        return $friend['is_online'];
                    });
                    break;
                    
                case 'cs2':
                    $friends = array_filter($friends, function($friend) {
                        return $friend['has_cs2'];
                    });
                    break;
                    
                case 'high_level':
                    $friends = array_filter($friends, function($friend) {
                        return $friend['level'] >= 7;
                    });
                    break;
            }

            return response()->json([
                'success' => true,
                'friends' => array_values($friends),
                'total' => count($friends)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ], 500);
        }
    }
}