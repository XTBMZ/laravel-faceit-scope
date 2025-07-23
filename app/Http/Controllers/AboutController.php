<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Affiche la page À propos
     */
    public function index()
    {
        // Pas de fausses statistiques - on reste honnête
        $stats = [
            'status' => 'En développement',
            'data_source' => 'API FACEIT officielle',
            'developer' => 'XTBMZ',
            'algorithms_active' => 5
        ];

        return view('about', compact('stats'));
    }

    /**
     * Affiche la page Politique de confidentialité
     */
    public function privacy()
    {
        return view('privacy');
    }
}