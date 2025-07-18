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

        // Si on a les données du joueur, rediriger vers la page d'analyse
        if (isset($user['player_data']) && isset($user['player_data']['player_id'])) {
            return redirect()->route('advanced', [
                'playerId' => $user['player_data']['player_id'],
                'playerNickname' => $user['nickname']
            ]);
        }

        // Sinon, essayer de récupérer les données via l'API
        try {
            $playerData = $this->faceitService->getPlayerByNickname($user['nickname']);
            
            if ($playerData && isset($playerData['player_id'])) {
                return redirect()->route('advanced', [
                    'playerId' => $playerData['player_id'],
                    'playerNickname' => $user['nickname']
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Impossible de récupérer les données FACEIT du joueur connecté', [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'error' => $e->getMessage()
            ]);
        }

        // Fallback: afficher une page de profil basique
        return view('profile.index', [
            'user' => $user
        ]);
    }

    /**
     * Met à jour les données du profil
     */
    public function update(Request $request)
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            // Rafraîchir les données depuis FACEIT
            $updatedUserInfo = $this->faceitOAuth->getUserInfo($user['access_token']);
            
            // Récupérer les données complètes du joueur
            $playerData = null;
            if (isset($updatedUserInfo['sub'])) {
                try {
                    $playerData = $this->faceitOAuth->getPlayerData($user['access_token'], $updatedUserInfo['sub']);
                } catch (\Exception $e) {
                    Log::warning('Impossible de récupérer les données joueur lors de la mise à jour', [
                        'user_id' => $updatedUserInfo['sub'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Mettre à jour la session
            $userData = $this->faceitOAuth->storeUserSession($updatedUserInfo, $user['access_token'], $playerData);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $userData['id'],
                    'nickname' => $userData['nickname'],
                    'picture' => $userData['picture'],
                    'email' => $userData['email'],
                    'player_data' => $playerData
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour profil', [
                'error' => $e->getMessage(),
                'user_id' => $user['id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du profil'
            ], 500);
        }
    }

    /**
     * Synchronise les données FACEIT avec le profil
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
            
            // Récupérer les données via l'API FACEIT standard
            $playerData = $this->faceitService->getPlayerByNickname($user['nickname']);
            
            if (!$playerData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de trouver votre profil FACEIT'
                ], 404);
            }

            // Récupérer aussi les statistiques
            $playerStats = null;
            try {
                $playerStats = $this->faceitService->getPlayerStats($playerData['player_id']);
            } catch (\Exception $e) {
                Log::warning('Impossible de récupérer les stats lors de la sync', [
                    'player_id' => $playerData['player_id'],
                    'error' => $e->getMessage()
                ]);
            }

            // Mettre à jour les données utilisateur
            $userData = $user;
            $userData['player_data'] = $playerData;
            $userData['player_stats'] = $playerStats;
            $userData['synced_at'] = now()->timestamp;

            // Sauvegarder en session
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
                'message' => 'Données FACEIT synchronisées avec succès',
                'player_data' => $playerData,
                'has_stats' => $playerStats !== null
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

    /**
     * Récupère l'historique des matches du joueur connecté
     */
    public function getMatchHistory(Request $request)
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
            $limit = min((int) $request->get('limit', 20), 100);

            // Récupérer l'historique via l'API FACEIT
            $history = $this->faceitService->getPlayerHistory($playerId, 0, 0, $offset, $limit);

            return response()->json([
                'success' => true,
                'history' => $history,
                'player_id' => $playerId
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération historique', [
                'error' => $e->getMessage(),
                'user_id' => $user['id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération de l\'historique'
            ], 500);
        }
    }

    /**
     * Exporte les données du profil
     */
    public function exportData()
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            return response()->json([
                'success' => false,
                'error' => 'Non authentifié'
            ], 401);
        }

        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            // Préparer les données à exporter (sans le token d'accès)
            $exportData = [
                'profile' => [
                    'id' => $user['id'],
                    'nickname' => $user['nickname'],
                    'email' => $user['email'],
                    'picture' => $user['picture'],
                    'logged_in_at' => $user['logged_in_at']
                ],
                'player_data' => $user['player_data'] ?? null,
                'exported_at' => now()->toISOString(),
                'export_version' => '1.0'
            ];

            // Récupérer les statistiques récentes si possible
            if (isset($user['player_data']['player_id'])) {
                try {
                    $stats = $this->faceitService->getPlayerStats($user['player_data']['player_id']);
                    $exportData['statistics'] = $stats;
                } catch (\Exception $e) {
                    Log::warning('Impossible d\'inclure les stats dans l\'export', [
                        'player_id' => $user['player_data']['player_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $filename = 'faceit-scope-profile-' . $user['nickname'] . '-' . date('Y-m-d') . '.json';

            return response()->json($exportData)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            Log::error('Erreur export données', [
                'error' => $e->getMessage(),
                'user_id' => $user['id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'export des données'
            ], 500);
        }
    }
}