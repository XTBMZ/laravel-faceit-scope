<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FaceitOAuthService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $endpoints;
    private $scopes;

    public function __construct()
    {
        $this->clientId = config('faceit.client_id');
        $this->clientSecret = config('faceit.client_secret');
        $this->redirectUri = config('faceit.redirect_uri');
        $this->endpoints = config('faceit.endpoints');
        $this->scopes = config('faceit.scopes');
    }

    /**
     * Génère une URL d'autorisation avec PKCE
     */
    public function getAuthorizationUrl()
    {
        // Générer le code verifier et le challenge pour PKCE
        $codeVerifier = $this->generateCodeVerifier();
        $codeChallenge = $this->generateCodeChallenge($codeVerifier);
        $state = Str::random(32);

        // Stocker en session pour la vérification
        Session::put(config('faceit.session.code_verifier_key'), $codeVerifier);
        Session::put(config('faceit.session.state_key'), $state);

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $this->scopes),
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ];

        $url = $this->endpoints['authorization'] . '?' . http_build_query($params);
        
        Log::info('FACEIT OAuth: URL d\'autorisation générée', [
            'url' => $url,
            'state' => $state,
            'code_challenge' => $codeChallenge
        ]);

        return $url;
    }

    /**
 * Échange de token - VERSION ALTERNATIVE avec debug maximal
 */
public function exchangeCodeForToken($authorizationCode, $state)
{
    // Vérifications préliminaires
    $sessionState = Session::get(config('faceit.session.state_key'));
    if (!$sessionState || $sessionState !== $state) {
        throw new \Exception('État OAuth invalide');
    }

    $codeVerifier = Session::get(config('faceit.session.code_verifier_key'));
    if (!$codeVerifier) {
        throw new \Exception('Code verifier manquant');
    }

    try {
        // Méthode alternative 1 : Utiliser cURL directement
        $postData = http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'redirect_uri' => $this->redirectUri,
            'code_verifier' => $codeVerifier,
            'client_id' => $this->clientId,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->endpoints['token'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'Content-Length: ' . strlen($postData)
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => fopen('php://temp', 'rw+'),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        Log::info('FACEIT OAuth: cURL Response', [
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $error,
            'post_data' => str_replace($authorizationCode, substr($authorizationCode, 0, 10) . '...', $postData)
        ]);

        curl_close($ch);

        if ($error) {
            throw new \Exception('Erreur cURL: ' . $error);
        }

        if ($httpCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpCode . ': ' . $response);
        }

        $tokenData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Réponse JSON invalide: ' . $response);
        }

        // Nettoyer la session
        Session::forget([
            config('faceit.session.state_key'),
            config('faceit.session.code_verifier_key')
        ]);

        return $tokenData;

    } catch (\Exception $e) {
        Log::error('FACEIT OAuth: Exception échange token (cURL)', [
            'error' => $e->getMessage(),
            'endpoint' => $this->endpoints['token'],
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri
        ]);
        throw $e;
    }
}

    /**
     * Récupère les informations utilisateur avec avatar - VERSION AMÉLIORÉE
     */
    public function getUserInfo($accessToken)
    {
        try {
            Log::info('FACEIT OAuth: Récupération userinfo avec avatar');
    
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(15)
                ->get($this->endpoints['userinfo']);
    
            if (!$response->successful()) {
                Log::error('FACEIT OAuth: Erreur récupération userinfo', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Erreur lors de la récupération des informations utilisateur: ' . $response->body());
            }
    
            $userInfo = $response->json();
            
            Log::info('FACEIT OAuth: UserInfo récupéré avec avatar', [
                'user_id' => $userInfo['sub'] ?? null,
                'email' => $userInfo['email'] ?? null,
                'nickname' => $userInfo['nickname'] ?? null,
                'has_picture' => !empty($userInfo['picture']),
                'picture_url' => $userInfo['picture'] ?? 'non fournie',
                'given_name' => $userInfo['given_name'] ?? null,
                'family_name' => $userInfo['family_name'] ?? null
            ]);
    
            // Si pas d'avatar via OAuth, essayer de le récupérer via l'API standard
            if (empty($userInfo['picture']) && isset($userInfo['sub'])) {
                Log::info('FACEIT OAuth: Tentative récupération avatar via API standard');
                
                try {
                    $playerData = $this->getPlayerData($accessToken, $userInfo['sub']);
                    if ($playerData && !empty($playerData['avatar'])) {
                        $userInfo['picture'] = $playerData['avatar'];
                        Log::info('FACEIT OAuth: Avatar récupéré via API standard', [
                            'avatar_url' => $playerData['avatar']
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('FACEIT OAuth: Impossible de récupérer avatar via API', [
                        'error' => $e->getMessage()
                    ]);
                }
            }
    
            return $userInfo;
    
        } catch (\Exception $e) {
            Log::error('FACEIT OAuth: Exception getUserInfo', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Récupère les données complètes du joueur FACEIT
     */
    public function getPlayerData($accessToken, $userId)
    {
        try {
            // Utiliser l'API FACEIT standard pour récupérer les données complètes
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.faceit.api_key', '9bcea3f9-2144-495e-be16-02d4eb1a811c'),
                'Content-Type' => 'application/json'
            ])->get("https://open.faceit.com/data/v4/players/{$userId}");

            if (!$response->successful()) {
                Log::warning('FACEIT OAuth: Impossible de récupérer les données joueur via API', [
                    'status' => $response->status(),
                    'user_id' => $userId
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FACEIT OAuth: Exception getPlayerData', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
            return null;
        }
    }
    
    /**
     * Détermine la meilleure URL d'avatar disponible
     */
    private function getBestAvatarUrl($userInfo, $playerData)
    {
        // Priorité 1: Avatar OAuth (scope profile)
        if (!empty($userInfo['picture'])) {
            return $userInfo['picture'];
        }
        
        // Priorité 2: Avatar depuis les données joueur API standard
        if ($playerData && !empty($playerData['avatar'])) {
            return $playerData['avatar'];
        }
        
        // Priorité 3: Avatar par défaut ou gravatar basé sur l'email
        if (!empty($userInfo['email'])) {
            $emailHash = md5(strtolower(trim($userInfo['email'])));
            return "https://www.gravatar.com/avatar/{$emailHash}?s=200&d=mp";
        }
        
        // Fallback: Avatar par défaut
        return null;
    }
    
    /**
     * Identifie la source de l'avatar pour logging
     */
    private function getAvatarSource($userInfo, $playerData)
    {
        if (!empty($userInfo['picture'])) {
            return 'oauth_profile_scope';
        }
        
        if ($playerData && !empty($playerData['avatar'])) {
            return 'api_player_data';
        }
        
        if (!empty($userInfo['email'])) {
            return 'gravatar_fallback';
        }
        
        return 'none';
    }

    /**
     * Stocke l'utilisateur avec fallback avatar - VERSION AMÉLIORÉE
     */
    public function storeUserSession($userInfo, $accessToken, $playerData = null)
    {
        // Déterminer la meilleure URL d'avatar disponible
        $avatarUrl = $this->getBestAvatarUrl($userInfo, $playerData);
        
        $userData = [
            'id' => $userInfo['sub'],
            'email' => $userInfo['email'] ?? null,
            'nickname' => $userInfo['nickname'] ?? null,
            'picture' => $avatarUrl,
            'given_name' => $userInfo['given_name'] ?? null,
            'family_name' => $userInfo['family_name'] ?? null,
            'locale' => $userInfo['locale'] ?? null,
            'email_verified' => $userInfo['email_verified'] ?? false,
            'access_token' => $accessToken,
            'logged_in_at' => now()->timestamp,
            'player_data' => $playerData,
        ];
    
        // Stocker en session
        Session::put(config('faceit.session.user_key'), $userData);
        Session::put(config('faceit.session.access_token_key'), $accessToken);
    
        // Stocker en cache
        $cacheKey = 'faceit_user_' . $userInfo['sub'];
        Cache::put($cacheKey, $userData, config('faceit.cache_duration'));
    
        Log::info('FACEIT OAuth: Utilisateur stocké avec avatar', [
            'user_id' => $userInfo['sub'],
            'nickname' => $userInfo['nickname'] ?? null,
            'has_avatar' => !empty($avatarUrl),
            'avatar_source' => $this->getAvatarSource($userInfo, $playerData),
            'has_player_data' => $playerData !== null
        ]);
    
        return $userData;
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public function getAuthenticatedUser()
    {
        return Session::get(config('faceit.session.user_key'));
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public function isAuthenticated()
    {
        $user = $this->getAuthenticatedUser();
        return $user !== null && isset($user['access_token']);
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        $user = $this->getAuthenticatedUser();
        
        if ($user) {
            // Supprimer du cache
            $cacheKey = 'faceit_user_' . $user['id'];
            Cache::forget($cacheKey);
            
            Log::info('FACEIT OAuth: Déconnexion utilisateur', [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'] ?? null
            ]);
        }

        // Nettoyer la session
        Session::forget([
            config('faceit.session.user_key'),
            config('faceit.session.access_token_key'),
            config('faceit.session.state_key'),
            config('faceit.session.code_verifier_key')
        ]);
    }

    /**
     * Génère un code verifier pour PKCE
     */
    private function generateCodeVerifier()
    {
        $length = config('faceit.pkce.code_verifier_length', 128);
        return Str::random($length);
    }

    /**
     * Génère un code challenge à partir du code verifier
     */
    private function generateCodeChallenge($codeVerifier)
    {
        return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    }

    /**
     * Rafraîchit le token d'accès (si supporté par FACEIT)
     */
    public function refreshAccessToken($refreshToken)
    {
        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                ])
                ->post($this->endpoints['token'], [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Erreur lors du rafraîchissement du token');
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FACEIT OAuth: Erreur refresh token', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}