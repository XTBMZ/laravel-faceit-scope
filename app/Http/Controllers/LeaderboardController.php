<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Affiche la page des classements - VERSION OPTIMISÉE
     * Plus de traitement côté serveur, tout est géré en JavaScript
     */
    public function index(Request $request)
    {
        // Valeurs par défaut simples (plus de validation complexe)
        $region = $request->get('region', 'EU');
        $country = $request->get('country', '');
        $limit = (int) $request->get('limit', 20);

        // Validation basique côté serveur (le JS gère le reste)
        $validRegions = ['EU', 'NA', 'SA', 'AS', 'AF', 'OC'];
        if (!in_array($region, $validRegions)) {
            $region = 'EU';
        }

        $validLimits = [20, 50, 100];
        if (!in_array($limit, $validLimits)) {
            $limit = 20;
        }
        
        return view('leaderboards', compact('region', 'country', 'limit'));
    }
}