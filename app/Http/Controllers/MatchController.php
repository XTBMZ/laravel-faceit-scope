<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Affiche la page d'analyse de match
     * SIMPLIFIÉE - Plus de traitement côté serveur
     */
    public function index(Request $request)
    {
        $matchId = $request->get('matchId');
        
        // Validation basique de l'ID
        if ($matchId && !$this->isValidMatchId($matchId)) {
            return redirect()->route('home')->with('error', 'ID de match invalide');
        }
        
        return view('match', compact('matchId'));
    }

    /**
     * Validation simple de l'ID de match
     */
    private function isValidMatchId($matchId)
    {
        // Format UUID standard
        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }
        
        // Format avec préfixe numérique
        if (preg_match('/^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $matchId)) {
            return true;
        }

        // Format court FACEIT
        if (preg_match('/^[a-f0-9]{24}$/i', $matchId)) {
            return true;
        }
        
        return false;
    }
}