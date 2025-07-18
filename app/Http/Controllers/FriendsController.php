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
        
        // Récupérer la liste des amis depuis le cache/session
        $friends = $this->getFriendsList($user['id']);
        
        // Récupérer les suggestions d'amis
        $suggestions = $this->getFriendSuggestions($user);

        return view('friends.index', [
            'user' => $user,
            'friends' => $friends,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Recherche des joueurs pour les ajouter en ami
     */
    public function searchPlayers(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'error' => 'Le nom doit contenir au moins 2 caractères'
            ]);
        }

        try {
            // Rechercher via l'API FACEIT
            $results = $this->faceitService->searchPlayers($query, null, 0, 10);
            
            $players = [];
            foreach ($results['items'] ?? [] as $player) {
                // Vérifier que le joueur a des stats CS2
                if (isset($player['games']['cs2']) || isset($player['games']['csgo'])) {
                    $players[] = [
                        'player_id' => $player['player_id'],
                        'nickname' => $player['nickname'],
                        'avatar' => $player['avatar'] ?? null,
                        'country' => $player['country'] ?? 'EU',
                        'skill_level' => $player['games']['cs2']['skill_level'] ?? $player['games']['csgo']['skill_level'] ?? 1,
                        'faceit_elo' => $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'players' => $players
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur recherche joueurs:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la recherche'
            ]);
        }
    }

    /**
     * Ajoute un ami
     */
    public function addFriend(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'player_id' => 'required|string',
            'nickname' => 'required|string|max:255'
        ]);

        $user = $this->faceitOAuth->getAuthenticatedUser();
        $playerId = $request->get('player_id');
        $nickname = $request->get('nickname');

        try {
            // Vérifier que le joueur existe
            $player = $this->faceitService->getPlayer($playerId);
            
            if (!$player) {
                return response()->json([
                    'success' => false,
                    'error' => 'Joueur non trouvé'
                ]);
            }

            // Ajouter à la liste d'amis
            $friends = $this->getFriendsList($user['id']);
            
            // Vérifier qu'il n'est pas déjà ami
            foreach ($friends as $friend) {
                if ($friend['player_id'] === $playerId) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Ce joueur est déjà dans votre liste d\'amis'
                    ]);
                }
            }

            // Ajouter le nouvel ami
            $newFriend = [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'avatar' => $player['avatar'] ?? null,
                'country' => $player['country'] ?? 'EU',
                'skill_level' => $player['games']['cs2']['skill_level'] ?? $player['games']['csgo']['skill_level'] ?? 1,
                'faceit_elo' => $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000,
                'added_at' => now()->timestamp,
                'status' => 'online' // Simulation du statut
            ];

            $friends[] = $newFriend;
            $this->saveFriendsList($user['id'], $friends);

            Log::info('Ami ajouté', [
                'user_id' => $user['id'],
                'friend_added' => $player['nickname']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ami ajouté avec succès !',
                'friend' => $newFriend
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur ajout ami:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'ajout de l\'ami'
            ]);
        }
    }

    /**
     * Supprime un ami
     */
    public function removeFriend(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'player_id' => 'required|string'
        ]);

        $user = $this->faceitOAuth->getAuthenticatedUser();
        $playerId = $request->get('player_id');

        try {
            $friends = $this->getFriendsList($user['id']);
            
            // Filtrer pour supprimer l'ami
            $friends = array_filter($friends, function($friend) use ($playerId) {
                return $friend['player_id'] !== $playerId;
            });

            $friends = array_values($friends); // Réindexer
            $this->saveFriendsList($user['id'], $friends);

            return response()->json([
                'success' => true,
                'message' => 'Ami supprimé de votre liste'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression ami:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression'
            ]);
        }
    }

    /**
     * Compare avec un ami
     */
    public function compareWithFriend(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'friend_nickname' => 'required|string'
        ]);

        $user = $this->faceitOAuth->getAuthenticatedUser();
        $friendNickname = $request->get('friend_nickname');

        return response()->json([
            'success' => true,
            'redirect_url' => route('comparison') . '?player1=' . urlencode($user['nickname']) . '&player2=' . urlencode($friendNickname)
        ]);
    }

    /**
     * Met à jour les statistiques des amis
     */
    public function updateFriendsStats(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $user = $this->faceitOAuth->getAuthenticatedUser();

        try {
            $friends = $this->getFriendsList($user['id']);
            $updatedFriends = [];

            foreach ($friends as $friend) {
                try {
                    // Récupérer les données actuelles du joueur
                    $playerData = $this->faceitService->getPlayer($friend['player_id']);
                    
                    if ($playerData) {
                        $friend['skill_level'] = $playerData['games']['cs2']['skill_level'] ?? $playerData['games']['csgo']['skill_level'] ?? $friend['skill_level'];
                        $friend['faceit_elo'] = $playerData['games']['cs2']['faceit_elo'] ?? $playerData['games']['csgo']['faceit_elo'] ?? $friend['faceit_elo'];
                        $friend['avatar'] = $playerData['avatar'] ?? $friend['avatar'];
                        $friend['last_updated'] = now()->timestamp;
                    }

                    $updatedFriends[] = $friend;

                } catch (\Exception $e) {
                    // Garder les anciennes données si erreur
                    $updatedFriends[] = $friend;
                    Log::warning('Impossible de mettre à jour ami: ' . $friend['nickname']);
                }
            }

            $this->saveFriendsList($user['id'], $updatedFriends);

            return response()->json([
                'success' => true,
                'message' => 'Statistiques mises à jour',
                'friends' => $updatedFriends
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour amis:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour'
            ]);
        }
    }

    /**
     * Récupère la liste des amis depuis le stockage
     */
    private function getFriendsList($userId)
    {
        $cacheKey = "friends_list_{$userId}";
        return Cache::get($cacheKey, []);
    }

    /**
     * Sauvegarde la liste des amis
     */
    private function saveFriendsList($userId, $friends)
    {
        $cacheKey = "friends_list_{$userId}";
        Cache::put($cacheKey, $friends, 86400); // 24 heures
    }

    /**
     * Génère des suggestions d'amis basées sur le niveau et la région
     */
    private function getFriendSuggestions($user)
    {
        if (!isset($user['player_data'])) {
            return [];
        }

        $playerData = $user['player_data'];
        $userLevel = $playerData['games']['cs2']['skill_level'] ?? $playerData['games']['csgo']['skill_level'] ?? 5;
        $userCountry = $playerData['country'] ?? 'EU';

        // Cache les suggestions pour éviter trop d'appels API
        $cacheKey = "friend_suggestions_{$user['id']}";
        
        return Cache::remember($cacheKey, 3600, function () use ($userLevel, $userCountry) {
            try {
                // Rechercher des joueurs avec un niveau similaire
                $suggestions = [];
                
                // Simuler quelques suggestions (en production, utiliser l'API FACEIT search)
                $samplePlayers = [
                    ['nickname' => 'ProGamer2024', 'level' => $userLevel, 'country' => $userCountry],
                    ['nickname' => 'CS2Legend', 'level' => $userLevel - 1, 'country' => $userCountry],
                    ['nickname' => 'FaceitKing', 'level' => $userLevel + 1, 'country' => $userCountry],
                ];

                foreach ($samplePlayers as $sample) {
                    try {
                        $player = $this->faceitService->getPlayerByNickname($sample['nickname']);
                        if ($player && isset($player['games']['cs2'])) {
                            $suggestions[] = [
                                'player_id' => $player['player_id'],
                                'nickname' => $player['nickname'],
                                'avatar' => $player['avatar'] ?? null,
                                'country' => $player['country'] ?? 'EU',
                                'skill_level' => $player['games']['cs2']['skill_level'] ?? 1,
                                'faceit_elo' => $player['games']['cs2']['faceit_elo'] ?? 1000,
                                'reason' => 'Niveau similaire'
                            ];
                        }
                    } catch (\Exception $e) {
                        // Ignorer les erreurs pour les suggestions
                        continue;
                    }
                }

                return array_slice($suggestions, 0, 3);

            } catch (\Exception $e) {
                return [];
            }
        });
    }
}