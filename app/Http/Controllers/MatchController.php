<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    protected $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    public function index(Request $request)
    {
        $matchId = $request->get('matchId');
        return view('match', compact('matchId'));
    }

    public function getMatchData(Request $request, $matchId)
    {
        try {
            
            $cleanMatchId = $this->faceitService->extractMatchId($matchId);
            
            
            $match = $this->faceitService->getMatch($cleanMatchId);
            
            if (!$match) {
                return response()->json(['error' => 'Match non trouvé'], 404);
            }

            
            $matchStats = null;
            try {
                $matchStats = $this->faceitService->getMatchStats($cleanMatchId);
            } catch (\Exception $e) {
                Log::warning("Impossible de récupérer les stats du match {$cleanMatchId}: " . $e->getMessage());
            }

            
            $enrichedMatch = $this->enrichMatchData($match);
            
            
            $analysis = $this->performMatchAnalysis($enrichedMatch);

            return response()->json([
                'success' => true,
                'match' => $enrichedMatch,
                'matchStats' => $matchStats,
                'analysis' => $analysis
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération match data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function enrichMatchData($match)
    {
        $enrichedTeams = [];

        foreach ($match['teams'] as $teamId => $team) {
            $enrichedPlayers = [];
            
            foreach ($team['roster'] as $player) {
                try {
                    
                    $playerData = $this->faceitService->getPlayer($player['player_id']);
                    $playerStats = $this->faceitService->getPlayerStats($player['player_id']);
                    
                    
                    $playerAnalysis = $this->analyzePlayerPerformance($playerData, $playerStats);
                    
                    $enrichedPlayers[] = array_merge($player, [
                        'full_data' => $playerData,
                        'stats' => $playerStats,
                        'analysis' => $playerAnalysis
                    ]);
                    
                } catch (\Exception $e) {
                    Log::warning("Impossible d'enrichir les données du joueur {$player['player_id']}: " . $e->getMessage());
                    $enrichedPlayers[] = $player;
                }
            }
            
            $enrichedTeams[$teamId] = array_merge($team, [
                'roster' => $enrichedPlayers
            ]);
        }

        return array_merge($match, ['teams' => $enrichedTeams]);
    }

    private function analyzePlayerPerformance($playerData, $playerStats)
    {
        if (!$playerStats || !isset($playerStats['lifetime'])) {
            return ['error' => 'Statistiques non disponibles'];
        }

        $lifetime = $playerStats['lifetime'];
        $segments = $playerStats['segments'] ?? [];

        
        $matches = intval($lifetime['Matches'] ?? 0);
        $winRate = floatval($lifetime['Win Rate %'] ?? 0);
        $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
        $hsRate = floatval($lifetime['Average Headshots %'] ?? 0);
        $avgKills = floatval($lifetime['Average Kills'] ?? 0);

        
        $mapAnalysis = $this->analyzePlayerMaps($segments);
        
        
        $threatLevel = $this->calculateThreatLevel($playerData, $lifetime, $mapAnalysis);
        
        
        $performanceMetrics = $this->calculateAdvancedMetrics($lifetime);

        return [
            'threat_level' => $threatLevel,
            'best_map' => $mapAnalysis['best'],
            'worst_map' => $mapAnalysis['worst'],
            'performance_metrics' => $performanceMetrics,
            'key_stats' => [
                'matches' => $matches,
                'win_rate' => $winRate,
                'kd_ratio' => $kd,
                'headshot_rate' => $hsRate,
                'avg_kills' => $avgKills
            ]
        ];
    }

    private function analyzePlayerMaps($segments)
    {
        $mapStats = [];
        
        foreach ($segments as $segment) {
            if ($segment['type'] !== 'Map') continue;
            
            $mapName = $segment['label'];
            $matches = intval($segment['stats']['Matches'] ?? 0);
            $wins = intval($segment['stats']['Wins'] ?? 0);
            $kd = floatval($segment['stats']['Average K/D Ratio'] ?? 0);
            $hs = floatval($segment['stats']['Average Headshots %'] ?? 0);
            $kills = floatval($segment['stats']['Average Kills'] ?? 0);
            
            
            if ($matches < 3) continue;
            
            $winRate = $matches > 0 ? ($wins / $matches) * 100 : 0;
            
            
            $performanceScore = $this->calculateMapPerformanceScore($winRate, $kd, $hs, $kills, $matches);
            
            $mapStats[] = [
                'name' => $this->getCleanMapName($mapName),
                'matches' => $matches,
                'win_rate' => $winRate,
                'kd_ratio' => $kd,
                'headshot_rate' => $hs,
                'avg_kills' => $kills,
                'performance_score' => $performanceScore
            ];
        }
        
        if (empty($mapStats)) {
            return ['best' => null, 'worst' => null];
        }
        
        
        usort($mapStats, function($a, $b) {
            return $b['performance_score'] <=> $a['performance_score'];
        });
        
        return [
            'best' => $mapStats[0] ?? null,
            'worst' => end($mapStats) ?: null,
            'all' => $mapStats
        ];
    }

    private function calculateMapPerformanceScore($winRate, $kd, $hsRate, $avgKills, $matches)
    {
        
        $winRateWeight = 0.4;
        $kdWeight = 0.25;
        $hsWeight = 0.15;
        $killsWeight = 0.15;
        $experienceWeight = 0.05;
        
        
        $normalizedWinRate = min($winRate / 70, 1); 
        $normalizedKD = min($kd / 1.3, 1); 
        $normalizedHS = min($hsRate / 55, 1); 
        $normalizedKills = min($avgKills / 20, 1); 
        $experienceBonus = min($matches / 50, 1); 
        
        return ($normalizedWinRate * $winRateWeight) +
               ($normalizedKD * $kdWeight) +
               ($normalizedHS * $hsWeight) +
               ($normalizedKills * $killsWeight) +
               ($experienceBonus * $experienceWeight);
    }

    private function calculateThreatLevel($playerData, $lifetime, $mapAnalysis)
    {
        $elo = $playerData['games']['cs2']['faceit_elo'] ?? 1000;
        $level = $playerData['games']['cs2']['skill_level'] ?? 1;
        $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
        $winRate = floatval($lifetime['Win Rate %'] ?? 0);
        $matches = intval($lifetime['Matches'] ?? 0);
        
        
        $eloScore = min(($elo - 1000) / 2000, 1) * 25; 
        $levelScore = ($level / 10) * 20; 
        $kdScore = min($kd / 1.5, 1) * 25; 
        $winRateScore = ($winRate / 100) * 20; 
        $experienceScore = min($matches / 1000, 1) * 10; 
        
        $totalScore = $eloScore + $levelScore + $kdScore + $winRateScore + $experienceScore;
        
        return [
            'score' => round($totalScore, 1),
            'level' => $this->getThreatLevelName($totalScore),
            'color' => $this->getThreatLevelColor($totalScore)
        ];
    }

    private function calculateAdvancedMetrics($lifetime)
    {
        $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
        $hsRate = floatval($lifetime['Average Headshots %'] ?? 0);
        $winRate = floatval($lifetime['Win Rate %'] ?? 0);
        $avgKills = floatval($lifetime['Average Kills'] ?? 0);
        
        return [
            'consistency' => $this->calculateConsistency($kd, $hsRate, $winRate),
            'aggressiveness' => $this->calculateAggressiveness($avgKills, $kd),
            'precision' => $this->calculatePrecision($hsRate, $kd),
            'impact' => $this->calculateImpact($kd, $winRate, $avgKills)
        ];
    }

    private function calculateConsistency($kd, $hsRate, $winRate)
    {
        
        $kdNorm = min($kd / 1.2, 1);
        $hsNorm = min($hsRate / 50, 1);
        $winNorm = $winRate / 100;
        
        return round(($kdNorm + $hsNorm + $winNorm) / 3 * 100, 1);
    }

    private function calculateAggressiveness($avgKills, $kd)
    {
        
        $killsScore = min($avgKills / 18, 1) * 60;
        $kdScore = min($kd / 1.5, 1) * 40;
        
        return round($killsScore + $kdScore, 1);
    }

    private function calculatePrecision($hsRate, $kd)
    {
        
        $hsScore = min($hsRate / 60, 1) * 70;
        $kdBonus = min($kd / 1.3, 1) * 30;
        
        return round($hsScore + $kdBonus, 1);
    }

    private function calculateImpact($kd, $winRate, $avgKills)
    {
        
        $kdWeight = 0.4;
        $winWeight = 0.4;
        $killsWeight = 0.2;
        
        $kdScore = min($kd / 1.4, 1) * 100 * $kdWeight;
        $winScore = ($winRate / 100) * 100 * $winWeight;
        $killsScore = min($avgKills / 20, 1) * 100 * $killsWeight;
        
        return round($kdScore + $winScore + $killsScore, 1);
    }

    private function performMatchAnalysis($match)
    {
        $teams = $match['teams'];
        $teamAnalysis = [];
        
        foreach ($teams as $teamId => $team) {
            $teamAnalysis[$teamId] = $this->analyzeTeam($team);
        }
        
        
        $predictions = $this->generateMatchPredictions($teamAnalysis, $teams);
        
        
        $mapRecommendations = $this->generateMapRecommendations($teamAnalysis);
        
        return [
            'teams' => $teamAnalysis,
            'predictions' => $predictions,
            'map_recommendations' => $mapRecommendations,
            'key_matchups' => $this->identifyKeyMatchups($teams)
        ];
    }

    private function analyzeTeam($team)
    {
        $players = $team['roster'];
        $threatLevels = [];
        $bestMaps = [];
        $worstMaps = [];
        $avgStats = [
            'elo' => 0,
            'level' => 0,
            'kd' => 0,
            'win_rate' => 0,
            'threat_score' => 0
        ];
        
        $validPlayers = 0;
        
        foreach ($players as $player) {
            if (!isset($player['analysis']) || isset($player['analysis']['error'])) {
                continue;
            }
            
            $analysis = $player['analysis'];
            $playerData = $player['full_data'];
            $stats = $player['stats'];
            
            $validPlayers++;
            
            
            $avgStats['elo'] += $playerData['games']['cs2']['faceit_elo'] ?? 1000;
            $avgStats['level'] += $playerData['games']['cs2']['skill_level'] ?? 1;
            $avgStats['kd'] += floatval($stats['lifetime']['Average K/D Ratio'] ?? 0);
            $avgStats['win_rate'] += floatval($stats['lifetime']['Win Rate %'] ?? 0);
            $avgStats['threat_score'] += $analysis['threat_level']['score'];
            
            $threatLevels[] = $analysis['threat_level'];
            
            if ($analysis['best_map']) {
                $bestMaps[] = $analysis['best_map'];
            }
            if ($analysis['worst_map']) {
                $worstMaps[] = $analysis['worst_map'];
            }
        }
        
        
        if ($validPlayers > 0) {
            foreach ($avgStats as $key => $value) {
                $avgStats[$key] = round($value / $validPlayers, 1);
            }
        }
        
        return [
            'average_stats' => $avgStats,
            'threat_levels' => $threatLevels,
            'team_maps' => $this->calculateTeamMapPreferences($bestMaps, $worstMaps),
            'top_fragger' => $this->identifyTopFragger($players),
            'support_player' => $this->identifySupportPlayer($players),
            'team_strength' => $this->calculateTeamStrength($avgStats, $threatLevels)
        ];
    }

    private function calculateTeamMapPreferences($bestMaps, $worstMaps)
    {
        $mapCounts = ['best' => [], 'worst' => []];
        
        
        foreach ($bestMaps as $map) {
            $mapName = $map['name'];
            if (!isset($mapCounts['best'][$mapName])) {
                $mapCounts['best'][$mapName] = ['count' => 0, 'avg_score' => 0, 'total_score' => 0];
            }
            $mapCounts['best'][$mapName]['count']++;
            $mapCounts['best'][$mapName]['total_score'] += $map['performance_score'];
        }
        
        foreach ($worstMaps as $map) {
            $mapName = $map['name'];
            if (!isset($mapCounts['worst'][$mapName])) {
                $mapCounts['worst'][$mapName] = ['count' => 0, 'avg_score' => 0, 'total_score' => 0];
            }
            $mapCounts['worst'][$mapName]['count']++;
            $mapCounts['worst'][$mapName]['total_score'] += $map['performance_score'];
        }
        
        
        foreach ($mapCounts['best'] as $mapName => &$data) {
            $data['avg_score'] = $data['total_score'] / $data['count'];
        }
        
        foreach ($mapCounts['worst'] as $mapName => &$data) {
            $data['avg_score'] = $data['total_score'] / $data['count'];
        }
        
        
        uasort($mapCounts['best'], function($a, $b) {
            if ($a['count'] == $b['count']) {
                return $b['avg_score'] <=> $a['avg_score'];
            }
            return $b['count'] <=> $a['count'];
        });
        
        uasort($mapCounts['worst'], function($a, $b) {
            if ($a['count'] == $b['count']) {
                return $a['avg_score'] <=> $b['avg_score'];
            }
            return $b['count'] <=> $a['count'];
        });
        
        return [
            'preferred' => array_keys($mapCounts['best'])[0] ?? null,
            'avoid' => array_keys($mapCounts['worst'])[0] ?? null,
            'detailed' => $mapCounts
        ];
    }

    private function generateMatchPredictions($teamAnalysis, $teams)
    {
        $team1Stats = $teamAnalysis[array_keys($teamAnalysis)[0]]['average_stats'];
        $team2Stats = $teamAnalysis[array_keys($teamAnalysis)[1]]['average_stats'];
        
        
        $eloAdvantage = ($team1Stats['elo'] - $team2Stats['elo']) / 100;
        $threatAdvantage = ($team1Stats['threat_score'] - $team2Stats['threat_score']) / 10;
        $kdAdvantage = ($team1Stats['kd'] - $team2Stats['kd']) * 30;
        
        $team1Advantage = $eloAdvantage + $threatAdvantage + $kdAdvantage;
        
        
        $team1WinProb = 50 + min(max($team1Advantage, -45), 45);
        $team2WinProb = 100 - $team1WinProb;
        
        
        $potentialMVP = $this->predictMVP($teamAnalysis, $teams);
        
        return [
            'winner' => [
                'team' => $team1WinProb > $team2WinProb ? 'faction1' : 'faction2',
                'probability' => round(max($team1WinProb, $team2WinProb), 1),
                'confidence' => $this->calculatePredictionConfidence($team1WinProb, $team2WinProb)
            ],
            'probabilities' => [
                'faction1' => round($team1WinProb, 1),
                'faction2' => round($team2WinProb, 1)
            ],
            'mvp_prediction' => $potentialMVP,
            'key_factors' => $this->identifyKeyFactors($team1Stats, $team2Stats)
        ];
    }

    private function predictMVP($teamAnalysis, $teams)
    {
        $allPlayers = [];
        
        foreach ($teams as $teamId => $team) {
            foreach ($team['roster'] as $player) {
                if (!isset($player['analysis']) || isset($player['analysis']['error'])) {
                    continue;
                }
                
                $mvpScore = $this->calculateMVPScore($player);
                $allPlayers[] = [
                    'player' => $player,
                    'mvp_score' => $mvpScore,
                    'team' => $teamId
                ];
            }
        }
        
        
        usort($allPlayers, function($a, $b) {
            return $b['mvp_score'] <=> $a['mvp_score'];
        });
        
        return $allPlayers[0] ?? null;
    }

    private function calculateMVPScore($player)
    {
        if (!isset($player['analysis']) || isset($player['analysis']['error'])) {
            return 0;
        }
        
        $analysis = $player['analysis'];
        $stats = $player['stats']['lifetime'];
        
        $threatScore = $analysis['threat_level']['score'];
        $kd = floatval($stats['Average K/D Ratio'] ?? 0);
        $avgKills = floatval($stats['Average Kills'] ?? 0);
        $winRate = floatval($stats['Win Rate %'] ?? 0);
        
        
        return ($threatScore * 0.3) + 
               ($kd * 25 * 0.25) + 
               ($avgKills * 2 * 0.25) + 
               ($winRate * 0.2);
    }

    private function getThreatLevelName($score)
    {
        if ($score >= 80) return 'Extrême';
        if ($score >= 65) return 'Élevé';
        if ($score >= 50) return 'Modéré';
        if ($score >= 35) return 'Faible';
        return 'Minimal';
    }

    private function getThreatLevelColor($score)
    {
        if ($score >= 80) return 'red';
        if ($score >= 65) return 'orange';
        if ($score >= 50) return 'yellow';
        if ($score >= 35) return 'blue';
        return 'gray';
    }

    private function getCleanMapName($mapLabel)
    {
        return ucfirst(str_replace(['de_', 'cs_'], '', $mapLabel));
    }

    private function identifyTopFragger($players)
    {
        $topFragger = null;
        $highestKills = 0;
        
        foreach ($players as $player) {
            if (!isset($player['stats']['lifetime'])) continue;
            
            $avgKills = floatval($player['stats']['lifetime']['Average Kills'] ?? 0);
            if ($avgKills > $highestKills) {
                $highestKills = $avgKills;
                $topFragger = $player;
            }
        }
        
        return $topFragger;
    }

    private function identifySupportPlayer($players)
    {
        
        $supportPlayer = null;
        $bestSupportScore = 0;
        
        foreach ($players as $player) {
            if (!isset($player['stats']['lifetime'])) continue;
            
            $stats = $player['stats']['lifetime'];
            $avgKills = floatval($stats['Average Kills'] ?? 0);
            $winRate = floatval($stats['Win Rate %'] ?? 0);
            $kd = floatval($stats['Average K/D Ratio'] ?? 0);
            
            
            $supportScore = ($winRate / 100 * 40) + ($kd * 30) - ($avgKills * 1.5);
            
            if ($supportScore > $bestSupportScore) {
                $bestSupportScore = $supportScore;
                $supportPlayer = $player;
            }
        }
        
        return $supportPlayer;
    }

    private function calculateTeamStrength($avgStats, $threatLevels)
    {
        $baseStrength = ($avgStats['threat_score'] / 100) * 50;
        $consistencyBonus = (5 - $this->calculateThreatVariance($threatLevels)) * 2;
        $eloBonus = min(($avgStats['elo'] - 1500) / 1000 * 20, 20);
        
        return min(max($baseStrength + $consistencyBonus + $eloBonus, 0), 100);
    }

    private function calculateThreatVariance($threatLevels)
    {
        if (count($threatLevels) < 2) return 0;
        
        $scores = array_column($threatLevels, 'score');
        $mean = array_sum($scores) / count($scores);
        $variance = 0;
        
        foreach ($scores as $score) {
            $variance += pow($score - $mean, 2);
        }
        
        return sqrt($variance / count($scores));
    }

    private function generateMapRecommendations($teamAnalysis)
    {
        $team1Maps = $teamAnalysis[array_keys($teamAnalysis)[0]]['team_maps'];
        $team2Maps = $teamAnalysis[array_keys($teamAnalysis)[1]]['team_maps'];
        
        return [
            'team1_should_pick' => $team1Maps['preferred'],
            'team1_should_ban' => $team2Maps['preferred'],
            'team2_should_pick' => $team2Maps['preferred'],
            'team2_should_ban' => $team1Maps['preferred'],
            'balanced_maps' => $this->findBalancedMaps($team1Maps, $team2Maps)
        ];
    }

    private function findBalancedMaps($team1Maps, $team2Maps)
    {
        
        $commonMaps = ['Mirage', 'Inferno', 'Dust2', 'Nuke', 'Overpass', 'Vertigo', 'Ancient'];
        $balanced = [];
        
        foreach ($commonMaps as $map) {
            if ($map !== $team1Maps['preferred'] && 
                $map !== $team2Maps['preferred'] &&
                $map !== $team1Maps['avoid'] && 
                $map !== $team2Maps['avoid']) {
                $balanced[] = $map;
            }
        }
        
        return array_slice($balanced, 0, 3);
    }

    private function identifyKeyMatchups($teams)
    {
        $team1Players = $teams[array_keys($teams)[0]]['roster'];
        $team2Players = $teams[array_keys($teams)[1]]['roster'];
        
        
        $keyMatchups = [];
        
        
        $team1TopFragger = $this->identifyTopFragger($team1Players);
        $team2TopFragger = $this->identifyTopFragger($team2Players);
        
        if ($team1TopFragger && $team2TopFragger) {
            $keyMatchups[] = [
                'type' => 'Top Fraggers',
                'player1' => $team1TopFragger,
                'player2' => $team2TopFragger,
                'importance' => 'high'
            ];
        }
        
        return $keyMatchups;
    }

    private function identifyKeyFactors($team1Stats, $team2Stats)
    {
        $factors = [];
        
        $eloDiff = abs($team1Stats['elo'] - $team2Stats['elo']);
        if ($eloDiff > 200) {
            $factors[] = [
                'factor' => 'Différence d\'ELO significative',
                'impact' => 'high',
                'description' => "Écart de {$eloDiff} points d'ELO"
            ];
        }
        
        $kdDiff = abs($team1Stats['kd'] - $team2Stats['kd']);
        if ($kdDiff > 0.3) {
            $factors[] = [
                'factor' => 'Différence de K/D importante',
                'impact' => 'medium',
                'description' => "Écart de {$kdDiff} en K/D moyen"
            ];
        }
        
        return $factors;
    }

    private function calculatePredictionConfidence($prob1, $prob2)
    {
        $diff = abs($prob1 - $prob2);
        if ($diff > 30) return 'Élevée';
        if ($diff > 15) return 'Modérée';
        return 'Faible';
    }
}