<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitOAuthService;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $faceitOAuth;
    protected $faceitService;

    public function __construct(FaceitOAuthService $faceitOAuth, FaceitService $faceitService)
    {
        $this->faceitOAuth = $faceitOAuth;
        $this->faceitService = $faceitService;
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function index()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté pour accéder à votre profil');
        }

        $user = $this->faceitOAuth->getAuthenticatedUser();
        
        if (!$user) {
            return redirect()->route('home')
                ->with('error', 'Impossible de récupérer vos informations de profil');
        }

        
        $playerStats = null;
        $recentMatches = null;
        
        if (isset($user['player_data']) && isset($user['player_data']['player_id'])) {
            try {
                
                $playerStats = $this->faceitService->getPlayerStats($user['player_data']['player_id']);
                
                
                $recentMatches = $this->faceitService->getPlayerHistory($user['player_data']['player_id'], 0, 0, 0, 5);
                
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer les stats pour le profil', [
                    'user_id' => $user['id'],
                    'player_id' => $user['player_data']['player_id'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return view('profile.dashboard', [
            'user' => $user,
            'playerStats' => $playerStats,
            'recentMatches' => $recentMatches
        ]);
    }

    /**
     * API pour récupérer les données de profil en JSON
     */
    public function getProfileData()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            $profileData = [
                'user' => [
                    'id' => $user['id'],
                    'nickname' => $user['nickname'],
                    'email' => $user['email'],
                    'picture' => $user['picture'],
                    'logged_in_at' => $user['logged_in_at']
                ],
                'player_data' => $user['player_data'] ?? null,
                'stats' => null,
                'recent_matches' => null
            ];

            
            if (isset($user['player_data']['player_id'])) {
                try {
                    $profileData['stats'] = $this->faceitService->getPlayerStats($user['player_data']['player_id']);
                    $profileData['recent_matches'] = $this->faceitService->getPlayerHistory($user['player_data']['player_id'], 0, 0, 0, 5);
                } catch (\Exception $e) {
                    Log::warning('Erreur récupération stats profil API', [
                        'player_id' => $user['player_data']['player_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $profileData
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération données profil', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des données'
            ], 500);
        }
    }

    /**
     * Met à jour les données du profil depuis FACEIT
     */
    public function syncFaceitData()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            
            $playerData = $this->faceitService->getPlayerByNickname($user['nickname']);
            
            if (!$playerData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de trouver votre profil FACEIT'
                ], 404);
            }

            
            $userData = $user;
            $userData['player_data'] = $playerData;
            $userData['synced_at'] = now()->timestamp;

            
            $this->faceitOAuth->storeUserSession(
                [
                    'sub' => $user['id'],
                    'nickname' => $user['nickname'],
                    'email' => $user['email'],
                    'picture' => $user['picture']
                ],
                $user['access_token'],
                $playerData
            );

            Log::info('Synchronisation FACEIT réussie', [
                'user_id' => $user['id'],
                'player_id' => $playerData['player_id'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil synchronisé avec succès',
                'player_data' => $playerData
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation FACEIT', [
                'error' => $e->getMessage(),
                'user_id' => $user['id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la synchronisation: ' . $e->getMessage()
            ], 500);
        }
    }
}