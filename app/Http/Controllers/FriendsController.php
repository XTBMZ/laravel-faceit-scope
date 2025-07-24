<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitOAuthService;
use Illuminate\Support\Facades\Log;

class FriendsController extends Controller
{
    protected $faceitOAuth;

    public function __construct(FaceitOAuthService $faceitOAuth)
    {
        $this->faceitOAuth = $faceitOAuth;
    }

    /**
     * Affiche la page des amis (nécessite une authentification)
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
     * SUPPRIMÉ - Les amis sont maintenant chargés directement via JavaScript
     * pour des performances optimales comme dans le projet HTML/JS
     */
    
    /**
     * API simple pour vérifier l'authentification et récupérer l'ID utilisateur
     * Le JavaScript se charge du reste via l'API FACEIT directe
     */
    public function getAuthenticatedUser()
    {
        try {
            if (!$this->faceitOAuth->isAuthenticated()) {
                return response()->json([
                    'authenticated' => false,
                    'user' => null
                ]);
            }

            $user = $this->faceitOAuth->getAuthenticatedUser();
            
            if (!$user) {
                return response()->json([
                    'authenticated' => false,
                    'user' => null
                ]);
            }

            
            $safeUserData = [
                'id' => $user['id'],
                'nickname' => $user['nickname'],
                'email' => $user['email'],
                'picture' => $user['picture'],
                'player_data' => $user['player_data'] ?? null
            ];

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
     * Stats simples sans traitement lourd
     */
    public function getFriendsStats()
    {
        try {
            if (!$this->faceitOAuth->isAuthenticated()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            
            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => 0,
                    'online' => 0,
                    'average_elo' => 0,
                    'highest_elo' => 0,
                    'message' => 'Stats calculées côté client pour optimiser les performances'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur stats amis', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du calcul des statistiques'
            ], 500);
        }
    }
}