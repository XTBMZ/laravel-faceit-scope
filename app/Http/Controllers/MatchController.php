<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;
use App\Services\MatchAnalysisService;

class MatchController extends Controller
{
    protected $faceitService;
    protected $matchAnalysisService;

    public function __construct(FaceitService $faceitService, MatchAnalysisService $matchAnalysisService)
    {
        $this->faceitService = $faceitService;
        $this->matchAnalysisService = $matchAnalysisService;
    }

    public function index(Request $request)
    {
        $matchId = $request->get('matchId');
        
        return view('match', compact('matchId'));
    }

    public function getMatchData($matchId)
    {
        try {
            // Récupérer les données du match
            $match = $this->faceitService->getMatch($matchId);
            
            if (!$match) {
                return response()->json(['error' => 'Match non trouvé'], 404);
            }

            // Récupérer les données des joueurs pour chaque équipe
            $team1Players = [];
            $team2Players = [];

            // Traitement de l'équipe 1 (faction1)
            if (isset($match['teams']['faction1']['roster'])) {
                foreach ($match['teams']['faction1']['roster'] as $rosterPlayer) {
                    try {
                        $player = $this->faceitService->getPlayer($rosterPlayer['player_id']);
                        $playerStats = $this->faceitService->getPlayerStats($rosterPlayer['player_id']);
                        
                        $player['stats'] = $playerStats;
                        $team1Players[] = $player;
                        
                        // Petite pause pour éviter de surcharger l'API
                        usleep(100000); // 0.1 seconde
                    } catch (\Exception $e) {
                        // En cas d'erreur, utiliser les données du roster
                        $team1Players[] = [
                            'player_id' => $rosterPlayer['player_id'],
                            'nickname' => $rosterPlayer['nickname'] ?? 'Joueur inconnu',
                            'avatar' => $rosterPlayer['avatar'] ?? null,
                            'country' => null,
                            'games' => [],
                            'stats' => null,
                            'error' => true
                        ];
                    }
                }
            }

            // Traitement de l'équipe 2 (faction2)
            if (isset($match['teams']['faction2']['roster'])) {
                foreach ($match['teams']['faction2']['roster'] as $rosterPlayer) {
                    try {
                        $player = $this->faceitService->getPlayer($rosterPlayer['player_id']);
                        $playerStats = $this->faceitService->getPlayerStats($rosterPlayer['player_id']);
                        
                        $player['stats'] = $playerStats;
                        $team2Players[] = $player;
                        
                        // Petite pause pour éviter de surcharger l'API
                        usleep(100000); // 0.1 seconde
                    } catch (\Exception $e) {
                        // En cas d'erreur, utiliser les données du roster
                        $team2Players[] = [
                            'player_id' => $rosterPlayer['player_id'],
                            'nickname' => $rosterPlayer['nickname'] ?? 'Joueur inconnu',
                            'avatar' => $rosterPlayer['avatar'] ?? null,
                            'country' => null,
                            'games' => [],
                            'stats' => null,
                            'error' => true
                        ];
                    }
                }
            }

            // Analyser les performances des équipes
            $teamAnalysis = $this->matchAnalysisService->analyzeTeams($team1Players, $team2Players);
            $mapRecommendations = $this->matchAnalysisService->analyzeMapRecommendations($team1Players, $team2Players);
            $matchPredictions = $this->matchAnalysisService->generateMatchPredictions($team1Players, $team2Players, $match);

            // Récupérer les statistiques du match si terminé
            $matchStats = null;
            if ($match['status'] === 'FINISHED') {
                try {
                    $matchStats = $this->faceitService->getMatchStats($matchId);
                } catch (\Exception $e) {
                    // Statistiques non disponibles
                }
            }

            return response()->json([
                'success' => true,
                'match' => $match,
                'team1Players' => $team1Players,
                'team2Players' => $team2Players,
                'teamAnalysis' => $teamAnalysis,
                'mapRecommendations' => $mapRecommendations,
                'matchPredictions' => $matchPredictions,
                'matchStats' => $matchStats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function getMatchStats($matchId)
    {
        try {
            $matchStats = $this->faceitService->getMatchStats($matchId);
            return response()->json($matchStats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}