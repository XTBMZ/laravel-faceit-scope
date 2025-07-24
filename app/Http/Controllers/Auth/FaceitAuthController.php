<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FaceitOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaceitAuthController extends Controller
{
    protected $faceitOAuth;

    public function __construct(FaceitOAuthService $faceitOAuth)
    {
        $this->faceitOAuth = $faceitOAuth;
    }

    /**
     * Redirige vers FACEIT pour l'authentification
     */
    public function redirectToFaceit()
    {
        try {
            $authUrl = $this->faceitOAuth->getAuthorizationUrl();
            
            Log::info('FACEIT Auth: Redirection vers FACEIT', [
                'url' => $authUrl
            ]);

            return redirect($authUrl);

        } catch (\Exception $e) {
            Log::error('FACEIT Auth: Erreur génération URL', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')->with('error', 'Erreur lors de la connexion à FACEIT');
        }
    }

    /**
     * Gère le callback de FACEIT
     */
    public function handleFaceitCallback(Request $request)
    {
        try {
            
            $authorizationCode = $request->get('code');
            $state = $request->get('state');
            $error = $request->get('error');

            if ($error) {
                Log::warning('FACEIT Auth: Erreur callback', [
                    'error' => $error,
                    'error_description' => $request->get('error_description')
                ]);

                return redirect()->route('home')->with('error', 'Connexion FACEIT annulée ou échouée');
            }

            if (!$authorizationCode) {
                Log::error('FACEIT Auth: Code d\'autorisation manquant');
                return redirect()->route('home')->with('error', 'Code d\'autorisation manquant');
            }

            if (!$state) {
                Log::error('FACEIT Auth: État manquant');
                return redirect()->route('home')->with('error', 'État OAuth manquant');
            }

            
            $tokenData = $this->faceitOAuth->exchangeCodeForToken($authorizationCode, $state);
            
            if (!isset($tokenData['access_token'])) {
                throw new \Exception('Token d\'accès manquant dans la réponse');
            }

            
            $userInfo = $this->faceitOAuth->getUserInfo($tokenData['access_token']);
            
            
            $playerData = null;
            if (isset($userInfo['sub'])) {
                $playerData = $this->faceitOAuth->getPlayerData($tokenData['access_token'], $userInfo['sub']);
            }

            
            $userData = $this->faceitOAuth->storeUserSession($userInfo, $tokenData['access_token'], $playerData);

            Log::info('FACEIT Auth: Connexion réussie', [
                'user_id' => $userData['id'],
                'nickname' => $userData['nickname'],
                'has_player_data' => $playerData !== null
            ]);

            
            $redirectUrl = $playerData && isset($playerData['player_id']) 
                ? route('advanced', ['playerId' => $playerData['player_id'], 'playerNickname' => $userData['nickname']])
                : route('home');

            return redirect($redirectUrl)->with('success', 'Connexion FACEIT réussie !');

        } catch (\Exception $e) {
            Log::error('FACEIT Auth: Erreur callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('home')->with('error', 'Erreur lors de la connexion FACEIT: ' . $e->getMessage());
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            $this->faceitOAuth->logout();

            Log::info('FACEIT Auth: Déconnexion', [
                'user_id' => $user['id'] ?? null,
                'nickname' => $user['nickname'] ?? null
            ]);

            return redirect()->route('home')->with('success', 'Déconnexion réussie');

        } catch (\Exception $e) {
            Log::error('FACEIT Auth: Erreur déconnexion', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')->with('error', 'Erreur lors de la déconnexion');
        }
    }

    /**
     * Récupère les informations de l'utilisateur connecté (API)
     */
    public function getCurrentUser()
    {
        try {
            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'authenticated' => false,
                    'user' => null
                ]);
            }

            
            $safeUserData = $user;
            unset($safeUserData['access_token']);

            return response()->json([
                'authenticated' => true,
                'user' => $safeUserData
            ]);

        } catch (\Exception $e) {
            Log::error('FACEIT Auth: Erreur getCurrentUser', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'authenticated' => false,
                'user' => null,
                'error' => 'Erreur lors de la récupération des données utilisateur'
            ], 500);
        }
    }

    /**
     * Vérifie le statut d'authentification
     */
    public function checkAuthStatus()
    {
        return response()->json([
            'authenticated' => $this->faceitOAuth->isAuthenticated()
        ]);
    }

    /**
     * Popup de connexion (pour JavaScript)
     */
    public function loginPopup()
    {
        try {
            $authUrl = $this->faceitOAuth->getAuthorizationUrl();
            
            return view('auth.faceit-popup', [
                'authUrl' => $authUrl
            ]);

        } catch (\Exception $e) {
            Log::error('FACEIT Auth: Erreur popup', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Erreur lors de la génération du popup de connexion'
            ], 500);
        }
    }

    /**
     * Page de callback pour popup (ferme la popup et informe le parent)
     */
    public function popupCallback(Request $request)
    {
        try {
            $authorizationCode = $request->get('code');
            $state = $request->get('state');
            $error = $request->get('error');

            if ($error) {
                return view('auth.popup-callback', [
                    'success' => false,
                    'error' => $request->get('error_description', 'Connexion annulée')
                ]);
            }

            if (!$authorizationCode || !$state) {
                return view('auth.popup-callback', [
                    'success' => false,
                    'error' => 'Paramètres manquants'
                ]);
            }

            
            $tokenData = $this->faceitOAuth->exchangeCodeForToken($authorizationCode, $state);
            $userInfo = $this->faceitOAuth->getUserInfo($tokenData['access_token']);
            
            $playerData = null;
            if (isset($userInfo['sub'])) {
                $playerData = $this->faceitOAuth->getPlayerData($tokenData['access_token'], $userInfo['sub']);
            }

            $userData = $this->faceitOAuth->storeUserSession($userInfo, $tokenData['access_token'], $playerData);

            return view('auth.popup-callback', [
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
            Log::error('FACEIT Auth: Erreur popup callback', [
                'error' => $e->getMessage()
            ]);

            return view('auth.popup-callback', [
                'success' => false,
                'error' => 'Erreur lors de la connexion: ' . $e->getMessage()
            ]);
        }
    }
}