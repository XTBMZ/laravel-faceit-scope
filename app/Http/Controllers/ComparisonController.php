<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;

class ComparisonController extends Controller
{
    protected $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    public function index(Request $request)
    {
        $player1 = $request->get('player1');
        $player2 = $request->get('player2');
        
        return view('comparison', compact('player1', 'player2'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'player1' => 'required|string|max:255',
            'player2' => 'required|string|max:255',
        ]);

        $player1Name = $request->get('player1');
        $player2Name = $request->get('player2');

        try {
            // Récupération des données des deux joueurs
            $player1 = $this->faceitService->getPlayerByNickname($player1Name);
            $player2 = $this->faceitService->getPlayerByNickname($player2Name);

            // Récupération des statistiques
            $player1Stats = $this->faceitService->getPlayerStats($player1['player_id']);
            $player2Stats = $this->faceitService->getPlayerStats($player2['player_id']);

            // Récupération de l'historique (optionnel)
            try {
                $player1History = $this->faceitService->getPlayerHistory($player1['player_id'], 0, 0, 0, 50);
                $player2History = $this->faceitService->getPlayerHistory($player2['player_id'], 0, 0, 0, 50);
            } catch (\Exception $e) {
                $player1History = null;
                $player2History = null;
            }

            // Analyse comparative
            $comparison = $this->performComparison($player1, $player2, $player1Stats, $player2Stats);

            return response()->json([
                'success' => true,
                'player1' => $player1,
                'player2' => $player2,
                'player1Stats' => $player1Stats,
                'player2Stats' => $player2Stats,
                'player1History' => $player1History,
                'player2History' => $player2History,
                'comparison' => $comparison
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function performComparison($player1, $player2, $player1Stats, $player2Stats)
    {
        // Calcul du gagnant global
        $overallWinner = $this->determineOverallWinner($player1, $player2, $player1Stats, $player2Stats);
        
        // Analyse des forces et faiblesses
        $strengthsWeaknesses = $this->analyzeStrengthsWeaknesses($player1Stats, $player2Stats);
        
        // Métriques avancées
        $performanceMetrics = [
            'player1' => $this->calculatePlayerMetrics($player1Stats),
            'player2' => $this->calculatePlayerMetrics($player2Stats)
        ];
        
        // Analyse des cartes
        $mapAnalysis = $this->analyzeMapPerformance($player1Stats, $player2Stats);
        
        // Suggestions d'amélioration
        $improvementSuggestions = $this->generateImprovementSuggestions($player1Stats, $player2Stats);
        
        // Analyse prédictive
        $predictiveAnalysis = $this->generatePredictiveAnalysis($player1, $player2);

        return [
            'overallWinner' => $overallWinner,
            'strengthsWeaknesses' => $strengthsWeaknesses,
            'performanceMetrics' => $performanceMetrics,
            'mapAnalysis' => $mapAnalysis,
            'improvementSuggestions' => $improvementSuggestions,
            'predictiveAnalysis' => $predictiveAnalysis
        ];
    }

    private function determineOverallWinner($player1, $player2, $player1Stats, $player2Stats)
    {
        $scores = ['player1' => 0, 'player2' => 0];
        
        // Critères de comparaison avec pondération
        $criteria = [
            ['key' => 'faceit_elo', 'weight' => 0.3, 'higher_better' => true],
            ['key' => 'skill_level', 'weight' => 0.2, 'higher_better' => true],
            ['key' => 'win_rate', 'weight' => 0.25, 'higher_better' => true],
            ['key' => 'kd_ratio', 'weight' => 0.15, 'higher_better' => true],
            ['key' => 'headshot_percentage', 'weight' => 0.1, 'higher_better' => true]
        ];
        
        foreach ($criteria as $criterion) {
            $value1 = $this->getPlayerValue($player1, $player1Stats, $criterion['key']);
            $value2 = $this->getPlayerValue($player2, $player2Stats, $criterion['key']);
            
            if ($criterion['higher_better']) {
                if ($value1 > $value2) $scores['player1'] += $criterion['weight'];
                else if ($value2 > $value1) $scores['player2'] += $criterion['weight'];
            } else {
                if ($value1 < $value2) $scores['player1'] += $criterion['weight'];
                else if ($value2 < $value1) $scores['player2'] += $criterion['weight'];
            }
        }
        
        $winner = $scores['player1'] > $scores['player2'] ? 'player1' : 'player2';
        $confidence = min(abs($scores['player1'] - $scores['player2']) * 100, 95);
        
        return [
            'winner' => $winner,
            'confidence' => $confidence,
            'scores' => $scores,
            'winnerData' => $winner === 'player1' ? $player1 : $player2
        ];
    }

    private function getPlayerValue($player, $stats, $key)
    {
        switch ($key) {
            case 'faceit_elo':
                return $player['games']['cs2']['faceit_elo'] ?? 0;
            case 'skill_level':
                return $player['games']['cs2']['skill_level'] ?? 0;
            case 'win_rate':
                return floatval($stats['lifetime']['Win Rate %'] ?? 0);
            case 'kd_ratio':
                return floatval($stats['lifetime']['Average K/D Ratio'] ?? 0);
            case 'headshot_percentage':
                return floatval($stats['lifetime']['Average Headshots %'] ?? 0);
            default:
                return 0;
        }
    }

    private function analyzeStrengthsWeaknesses($player1Stats, $player2Stats)
    {
        $metrics = [
            ['name' => 'K/D Ratio', 'value1' => floatval($player1Stats['lifetime']['Average K/D Ratio'] ?? 0), 'value2' => floatval($player2Stats['lifetime']['Average K/D Ratio'] ?? 0), 'threshold' => 0.2],
            ['name' => 'Headshots %', 'value1' => floatval($player1Stats['lifetime']['Average Headshots %'] ?? 0), 'value2' => floatval($player2Stats['lifetime']['Average Headshots %'] ?? 0), 'threshold' => 5],
            ['name' => 'Win Rate', 'value1' => floatval($player1Stats['lifetime']['Win Rate %'] ?? 0), 'value2' => floatval($player2Stats['lifetime']['Win Rate %'] ?? 0), 'threshold' => 5],
        ];
        
        $analysis = [
            'player1' => ['strengths' => [], 'weaknesses' => []],
            'player2' => ['strengths' => [], 'weaknesses' => []]
        ];
        
        foreach ($metrics as $metric) {
            $diff = $metric['value1'] - $metric['value2'];
            if (abs($diff) > $metric['threshold']) {
                if ($diff > 0) {
                    $analysis['player1']['strengths'][] = ['name' => $metric['name'], 'advantage' => $diff];
                    $analysis['player2']['weaknesses'][] = ['name' => $metric['name'], 'deficit' => -$diff];
                } else {
                    $analysis['player2']['strengths'][] = ['name' => $metric['name'], 'advantage' => -$diff];
                    $analysis['player1']['weaknesses'][] = ['name' => $metric['name'], 'deficit' => $diff];
                }
            }
        }
        
        return $analysis;
    }

    private function calculatePlayerMetrics($stats)
    {
        $matches = intval($stats['lifetime']['Matches'] ?? 0);
        $winRate = floatval($stats['lifetime']['Win Rate %'] ?? 0);
        $kd = floatval($stats['lifetime']['Average K/D Ratio'] ?? 0);
        $hs = floatval($stats['lifetime']['Average Headshots %'] ?? 0);
        
        $consistency = min(100, ($winRate + ($kd * 20) + $hs) / 3);
        $aggressiveness = min(100, $kd * 40 + ($hs - 40));
        $experience = min(100, log10($matches) * 25);
        
        return [
            'consistency' => max(0, $consistency),
            'aggressiveness' => max(0, $aggressiveness),
            'experience' => max(0, $experience),
            'overall' => ($consistency + $aggressiveness + $experience) / 3
        ];
    }

    private function analyzeMapPerformance($player1Stats, $player2Stats)
    {
        $maps1 = $this->extractMapStats($player1Stats);
        $maps2 = $this->extractMapStats($player2Stats);
        
        $commonMaps = array_intersect(array_keys($maps1), array_keys($maps2));
        $mapComparisons = [];
        
        foreach ($commonMaps as $mapName) {
            $map1 = $maps1[$mapName];
            $map2 = $maps2[$mapName];
            
            $mapComparisons[$mapName] = [
                'player1' => $map1,
                'player2' => $map2,
                'winner' => $map1['winRate'] > $map2['winRate'] ? 'player1' : 'player2',
                'advantage' => abs($map1['winRate'] - $map2['winRate'])
            ];
        }
        
        return $mapComparisons;
    }

    private function extractMapStats($stats)
    {
        $maps = [];
        $segments = array_filter($stats['segments'], function($segment) {
            return $segment['type'] === 'Map';
        });
        
        foreach ($segments as $segment) {
            $mapName = ucfirst(str_replace('de_', '', $segment['label']));
            $matches = intval($segment['stats']['Matches'] ?? 0);
            $wins = intval($segment['stats']['Wins'] ?? 0);
            
            $maps[$mapName] = [
                'matches' => $matches,
                'wins' => $wins,
                'winRate' => $matches > 0 ? round(($wins / $matches) * 100, 1) : 0,
                'kd' => round(floatval($segment['stats']['Average K/D Ratio'] ?? 0), 2)
            ];
        }
        
        return $maps;
    }

    private function generateImprovementSuggestions($player1Stats, $player2Stats)
    {
        $suggestions = ['player1' => [], 'player2' => []];
        
        // Analyse K/D
        $player1KD = floatval($player1Stats['lifetime']['Average K/D Ratio'] ?? 0);
        $player2KD = floatval($player2Stats['lifetime']['Average K/D Ratio'] ?? 0);
        
        if ($player1KD < 1.0) {
            $suggestions['player1'][] = [
                'priority' => 'high',
                'category' => 'Aim & Positioning',
                'suggestion' => 'Travailler le crosshair placement et les angles de tir',
                'impact' => 'Amélioration du K/D ratio'
            ];
        }
        
        if ($player2KD < 1.0) {
            $suggestions['player2'][] = [
                'priority' => 'high',
                'category' => 'Aim & Positioning',
                'suggestion' => 'Améliorer le pré-aim et la gestion des duels',
                'impact' => 'Réduction des morts inutiles'
            ];
        }
        
        // Analyse Headshots
        $player1HS = floatval($player1Stats['lifetime']['Average Headshots %'] ?? 0);
        $player2HS = floatval($player2Stats['lifetime']['Average Headshots %'] ?? 0);
        
        if ($player1HS < 45) {
            $suggestions['player1'][] = [
                'priority' => 'medium',
                'category' => 'Précision',
                'suggestion' => 'Entraînement aim_botz 30min/jour sur les headshots',
                'impact' => 'Augmentation des dégâts par balle'
            ];
        }
        
        if ($player2HS < 45) {
            $suggestions['player2'][] = [
                'priority' => 'medium',
                'category' => 'Précision',
                'suggestion' => 'Pratique du spray control et one-taps',
                'impact' => 'Efficacité accrue dans les duels'
            ];
        }
        
        return $suggestions;
    }

    private function generatePredictiveAnalysis($player1, $player2)
    {
        $elo1 = $player1['games']['cs2']['faceit_elo'] ?? 1000;
        $elo2 = $player2['games']['cs2']['faceit_elo'] ?? 1000;
        
        $eloDiff = abs($elo1 - $elo2);
        $matchupType = 'Équilibré';
        $winProbability = 50;
        
        if ($eloDiff > 200) {
            $matchupType = 'Déséquilibré';
            $winProbability = $elo1 > $elo2 ? 75 : 25;
        } elseif ($eloDiff > 100) {
            $matchupType = 'Légèrement déséquilibré';
            $winProbability = $elo1 > $elo2 ? 65 : 35;
        }
        
        return [
            'matchupType' => $matchupType,
            'winProbability' => [
                'player1' => $elo1 > $elo2 ? $winProbability : 100 - $winProbability,
                'player2' => $elo2 > $elo1 ? $winProbability : 100 - $winProbability
            ],
            'recommendation' => $eloDiff > 150 ? 
                "Match potentiellement déséquilibré. Le joueur avec l'ELO inférieur devrait se concentrer sur la défense." :
                "Match équilibré. La victoire dépendra de la forme du jour et de la synergie d'équipe."
        ];
    }
}

