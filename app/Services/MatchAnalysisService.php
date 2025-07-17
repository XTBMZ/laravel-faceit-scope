<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MatchAnalysisService
{
    /**
     * Analyse les performances des deux équipes
     */
    public function analyzeTeams(array $team1Players, array $team2Players): array
    {
        $team1Analysis = $this->analyzeTeam($team1Players, 'Team 1');
        $team2Analysis = $this->analyzeTeam($team2Players, 'Team 2');

        return [
            'team1' => $team1Analysis,
            'team2' => $team2Analysis,
            'comparison' => $this->compareTeams($team1Analysis, $team2Analysis)
        ];
    }

    /**
     * Analyse une équipe spécifique
     */
    private function analyzeTeam(array $players, string $teamName): array
    {
        $validPlayers = array_filter($players, function($player) {
            return !isset($player['error']) && isset($player['stats']);
        });

        if (empty($validPlayers)) {
            return [
                'teamName' => $teamName,
                'averageElo' => 0,
                'averageLevel' => 0,
                'averageKD' => 0,
                'averageWinRate' => 0,
                'averageHeadshots' => 0,
                'threatLevel' => 'low',
                'bestMaps' => [],
                'worstMaps' => [],
                'keyPlayers' => [],
                'playStyle' => 'unknown'
            ];
        }

        $totalElo = 0;
        $totalLevel = 0;
        $totalKD = 0;
        $totalWinRate = 0;
        $totalHeadshots = 0;
        $mapPerformances = [];

        foreach ($validPlayers as $player) {
            // ELO et niveau
            $elo = $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000;
            $level = $player['games']['cs2']['skill_level'] ?? $player['games']['csgo']['skill_level'] ?? 1;
            
            $totalElo += $elo;
            $totalLevel += $level;

            // Statistiques de base
            if (isset($player['stats']['lifetime'])) {
                $lifetime = $player['stats']['lifetime'];
                $totalKD += floatval($lifetime['Average K/D Ratio'] ?? 0);
                $totalWinRate += floatval($lifetime['Win Rate %'] ?? 0);
                $totalHeadshots += floatval($lifetime['Average Headshots %'] ?? 0);
            }

            // Analyse des cartes
            $playerMapPerfs = $this->analyzePlayerMaps($player);
            foreach ($playerMapPerfs as $map => $perf) {
                if (!isset($mapPerformances[$map])) {
                    $mapPerformances[$map] = [];
                }
                $mapPerformances[$map][] = $perf;
            }
        }

        $playerCount = count($validPlayers);
        $averageElo = $totalElo / $playerCount;
        $averageLevel = $totalLevel / $playerCount;
        $averageKD = $totalKD / $playerCount;
        $averageWinRate = $totalWinRate / $playerCount;
        $averageHeadshots = $totalHeadshots / $playerCount;

        // Calculer les meilleures et pires cartes de l'équipe
        $teamMaps = $this->calculateTeamMapPerformances($mapPerformances);

        return [
            'teamName' => $teamName,
            'averageElo' => round($averageElo),
            'averageLevel' => round($averageLevel, 1),
            'averageKD' => round($averageKD, 2),
            'averageWinRate' => round($averageWinRate, 1),
            'averageHeadshots' => round($averageHeadshots, 1),
            'threatLevel' => $this->calculateThreatLevel($averageElo, $averageKD, $averageWinRate),
            'bestMaps' => array_slice($teamMaps['best'], 0, 3),
            'worstMaps' => array_slice($teamMaps['worst'], 0, 3),
            'keyPlayers' => $this->identifyKeyPlayers($validPlayers),
            'playStyle' => $this->determinePlayStyle($validPlayers),
            'playerCount' => $playerCount
        ];
    }

    /**
     * Analyse les performances d'un joueur sur les cartes
     */
    private function analyzePlayerMaps(array $player): array
    {
        $mapPerformances = [];

        if (!isset($player['stats']['segments'])) {
            return $mapPerformances;
        }

        $mapSegments = array_filter($player['stats']['segments'], function($segment) {
            return isset($segment['type']) && $segment['type'] === 'Map';
        });

        foreach ($mapSegments as $segment) {
            $mapName = $this->getCleanMapName($segment['label'] ?? '');
            $stats = $segment['stats'] ?? [];

            $matches = intval($stats['Matches'] ?? 0);
            if ($matches < 3) continue; // Ignorer les cartes avec trop peu de matches

            $wins = intval($stats['Wins'] ?? 0);
            $winRate = $matches > 0 ? ($wins / $matches) * 100 : 0;
            $kd = floatval($stats['Average K/D Ratio'] ?? 0);
            $headshots = floatval($stats['Average Headshots %'] ?? 0);

            // Score de performance combiné
            $performanceScore = ($winRate * 0.4) + ($kd * 20 * 0.4) + ($headshots * 0.2);

            $mapPerformances[$mapName] = [
                'winRate' => $winRate,
                'kd' => $kd,
                'headshots' => $headshots,
                'matches' => $matches,
                'performanceScore' => $performanceScore
            ];
        }

        return $mapPerformances;
    }

    /**
     * Calcule les performances d'équipe sur les cartes
     */
    private function calculateTeamMapPerformances(array $mapPerformances): array
    {
        $teamMapScores = [];

        foreach ($mapPerformances as $map => $playerPerfs) {
            if (count($playerPerfs) < 2) continue; // Au moins 2 joueurs doivent avoir joué la carte

            $avgWinRate = array_sum(array_column($playerPerfs, 'winRate')) / count($playerPerfs);
            $avgKD = array_sum(array_column($playerPerfs, 'kd')) / count($playerPerfs);
            $avgHeadshots = array_sum(array_column($playerPerfs, 'headshots')) / count($playerPerfs);
            $totalMatches = array_sum(array_column($playerPerfs, 'matches'));
            $avgPerformanceScore = array_sum(array_column($playerPerfs, 'performanceScore')) / count($playerPerfs);

            $teamMapScores[$map] = [
                'name' => $map,
                'avgWinRate' => round($avgWinRate, 1),
                'avgKD' => round($avgKD, 2),
                'avgHeadshots' => round($avgHeadshots, 1),
                'totalMatches' => $totalMatches,
                'performanceScore' => $avgPerformanceScore,
                'playerCount' => count($playerPerfs)
            ];
        }

        // Trier par score de performance
        uasort($teamMapScores, function($a, $b) {
            return $b['performanceScore'] <=> $a['performanceScore'];
        });

        return [
            'best' => array_values($teamMapScores),
            'worst' => array_values(array_reverse($teamMapScores))
        ];
    }

    /**
     * Calcule le niveau de menace d'une équipe
     */
    private function calculateThreatLevel($averageElo, $averageKD, $averageWinRate): string
    {
        $score = 0;

        // ELO
        if ($averageElo >= 2000) $score += 3;
        elseif ($averageElo >= 1500) $score += 2;
        elseif ($averageElo >= 1200) $score += 1;

        // K/D
        if ($averageKD >= 1.3) $score += 3;
        elseif ($averageKD >= 1.1) $score += 2;
        elseif ($averageKD >= 0.9) $score += 1;

        // Win Rate
        if ($averageWinRate >= 65) $score += 3;
        elseif ($averageWinRate >= 55) $score += 2;
        elseif ($averageWinRate >= 45) $score += 1;

        if ($score >= 7) return 'extreme';
        if ($score >= 5) return 'high';
        if ($score >= 3) return 'medium';
        return 'low';
    }

    /**
     * Identifie les joueurs clés de l'équipe
     */
    private function identifyKeyPlayers(array $players): array
    {
        $keyPlayers = [];

        foreach ($players as $player) {
            if (isset($player['error']) || !isset($player['stats'])) continue;

            $lifetime = $player['stats']['lifetime'] ?? [];
            $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
            $winRate = floatval($lifetime['Win Rate %'] ?? 0);
            $elo = $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000;

            $influence = ($kd * 30) + ($winRate * 0.5) + ($elo * 0.01);

            $role = $this->determinePlayerRole($lifetime);

            $keyPlayers[] = [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'influence' => $influence,
                'role' => $role,
                'kd' => $kd,
                'winRate' => $winRate,
                'elo' => $elo
            ];
        }

        // Trier par influence
        usort($keyPlayers, function($a, $b) {
            return $b['influence'] <=> $a['influence'];
        });

        return array_slice($keyPlayers, 0, 3);
    }

    /**
     * Détermine le rôle d'un joueur
     */
    private function determinePlayerRole(array $lifetime): string
    {
        $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
        $entryRate = floatval($lifetime['Entry Rate'] ?? 0);
        $clutchRate = floatval($lifetime['1v1 Win Rate'] ?? 0);

        if ($entryRate > 0.15) return 'Entry Fragger';
        if ($clutchRate > 0.4) return 'Clutch Player';
        if ($kd > 1.2) return 'Fragger';
        return 'Support';
    }

    /**
     * Détermine le style de jeu de l'équipe
     */
    private function determinePlayStyle(array $players): string
    {
        $validPlayers = array_filter($players, function($player) {
            return !isset($player['error']) && isset($player['stats']);
        });

        if (empty($validPlayers)) return 'unknown';

        $totalKD = 0;
        $totalEntry = 0;
        $totalClutch = 0;

        foreach ($validPlayers as $player) {
            $lifetime = $player['stats']['lifetime'] ?? [];
            $totalKD += floatval($lifetime['Average K/D Ratio'] ?? 0);
            $totalEntry += floatval($lifetime['Entry Rate'] ?? 0);
            $totalClutch += floatval($lifetime['1v1 Win Rate'] ?? 0);
        }

        $avgKD = $totalKD / count($validPlayers);
        $avgEntry = $totalEntry / count($validPlayers);
        $avgClutch = $totalClutch / count($validPlayers);

        if ($avgEntry > 0.12) return 'Agressif';
        if ($avgClutch > 0.35) return 'Tactique';
        if ($avgKD > 1.15) return 'Fragging';
        return 'Équilibré';
    }

    /**
     * Compare deux équipes
     */
    private function compareTeams(array $team1, array $team2): array
    {
        $advantages = [];

        if ($team1['averageElo'] > $team2['averageElo']) {
            $advantages[] = [
                'team' => 'team1',
                'metric' => 'ELO supérieur',
                'difference' => $team1['averageElo'] - $team2['averageElo']
            ];
        } else {
            $advantages[] = [
                'team' => 'team2',
                'metric' => 'ELO supérieur',
                'difference' => $team2['averageElo'] - $team1['averageElo']
            ];
        }

        if ($team1['averageKD'] > $team2['averageKD']) {
            $advantages[] = [
                'team' => 'team1',
                'metric' => 'K/D supérieur',
                'difference' => round($team1['averageKD'] - $team2['averageKD'], 2)
            ];
        } else {
            $advantages[] = [
                'team' => 'team2',
                'metric' => 'K/D supérieur',
                'difference' => round($team2['averageKD'] - $team1['averageKD'], 2)
            ];
        }

        return $advantages;
    }

    /**
     * Analyse les recommandations de cartes
     */
    public function analyzeMapRecommendations(array $team1Players, array $team2Players): array
    {
        $team1Maps = $this->getTeamMapPerformances($team1Players);
        $team2Maps = $this->getTeamMapPerformances($team2Players);

        $recommendations = [];

        // Trouver les cartes communes
        $commonMaps = array_intersect_key($team1Maps, $team2Maps);

        foreach ($commonMaps as $map => $data) {
            $team1Perf = $team1Maps[$map];
            $team2Perf = $team2Maps[$map];

            $team1Advantage = $team1Perf['performanceScore'] - $team2Perf['performanceScore'];

            $recommendations[$map] = [
                'map' => $map,
                'team1Performance' => $team1Perf,
                'team2Performance' => $team2Perf,
                'advantage' => $team1Advantage,
                'recommendedFor' => $team1Advantage > 0 ? 'team1' : 'team2',
                'confidence' => min(abs($team1Advantage) * 10, 100)
            ];
        }

        // Trier par avantage
        uasort($recommendations, function($a, $b) {
            return abs($b['advantage']) <=> abs($a['advantage']);
        });

        return array_values($recommendations);
    }

    /**
     * Obtient les performances d'équipe sur les cartes
     */
    private function getTeamMapPerformances(array $players): array
    {
        $mapPerformances = [];

        foreach ($players as $player) {
            if (isset($player['error']) || !isset($player['stats'])) continue;

            $playerMaps = $this->analyzePlayerMaps($player);
            foreach ($playerMaps as $map => $perf) {
                if (!isset($mapPerformances[$map])) {
                    $mapPerformances[$map] = [];
                }
                $mapPerformances[$map][] = $perf;
            }
        }

        $teamMaps = [];
        foreach ($mapPerformances as $map => $playerPerfs) {
            if (count($playerPerfs) < 2) continue;

            $avgPerformanceScore = array_sum(array_column($playerPerfs, 'performanceScore')) / count($playerPerfs);
            $teamMaps[$map] = [
                'name' => $map,
                'performanceScore' => $avgPerformanceScore,
                'playerCount' => count($playerPerfs)
            ];
        }

        return $teamMaps;
    }

    /**
     * Génère les prédictions du match
     */
    public function generateMatchPredictions(array $team1Players, array $team2Players, array $matchData): array
    {
        $team1Analysis = $this->analyzeTeam($team1Players, 'Team 1');
        $team2Analysis = $this->analyzeTeam($team2Players, 'Team 2');

        // Prédiction du gagnant
        $winnerPrediction = $this->predictWinner($team1Analysis, $team2Analysis);

        // Prédiction MVP
        $mvpPrediction = $this->predictMVP([...$team1Players, ...$team2Players]);

        // Joueurs clés
        $keyPlayers = $this->predictKeyPlayers($team1Players, $team2Players);

        return [
            'winner' => $winnerPrediction,
            'mvp' => $mvpPrediction,
            'keyPlayers' => $keyPlayers,
            'confidence' => $this->calculatePredictionConfidence($team1Analysis, $team2Analysis)
        ];
    }

    /**
     * Prédit le gagnant du match
     */
    private function predictWinner(array $team1, array $team2): array
    {
        $team1Score = ($team1['averageElo'] / 100) + ($team1['averageKD'] * 50) + $team1['averageWinRate'];
        $team2Score = ($team2['averageElo'] / 100) + ($team2['averageKD'] * 50) + $team2['averageWinRate'];

        $winner = $team1Score > $team2Score ? 'team1' : 'team2';
        $confidence = min(abs($team1Score - $team2Score) / max($team1Score, $team2Score) * 100, 95);

        return [
            'winner' => $winner,
            'confidence' => round($confidence, 1),
            'team1Score' => round($team1Score, 1),
            'team2Score' => round($team2Score, 1),
            'reasoning' => $this->generateWinnerReasoning($team1, $team2, $winner)
        ];
    }

    /**
     * Prédit le MVP du match
     */
    private function predictMVP(array $allPlayers): ?array
    {
        $validPlayers = array_filter($allPlayers, function($player) {
            return !isset($player['error']) && isset($player['stats']);
        });

        if (empty($validPlayers)) return null;

        $mvpCandidates = [];

        foreach ($validPlayers as $player) {
            $lifetime = $player['stats']['lifetime'] ?? [];
            $kd = floatval($lifetime['Average K/D Ratio'] ?? 0);
            $winRate = floatval($lifetime['Win Rate %'] ?? 0);
            $elo = $player['games']['cs2']['faceit_elo'] ?? $player['games']['csgo']['faceit_elo'] ?? 1000;

            $mvpScore = ($kd * 40) + ($winRate * 0.3) + ($elo * 0.015);

            $mvpCandidates[] = [
                'player_id' => $player['player_id'],
                'nickname' => $player['nickname'],
                'mvpScore' => $mvpScore,
                'kd' => $kd,
                'winRate' => $winRate,
                'elo' => $elo
            ];
        }

        usort($mvpCandidates, function($a, $b) {
            return $b['mvpScore'] <=> $a['mvpScore'];
        });

        return $mvpCandidates[0] ?? null;
    }

    /**
     * Prédit les joueurs clés du match
     */
    private function predictKeyPlayers(array $team1Players, array $team2Players): array
    {
        $team1Key = $this->identifyKeyPlayers($team1Players);
        $team2Key = $this->identifyKeyPlayers($team2Players);

        return [
            'team1' => array_slice($team1Key, 0, 2),
            'team2' => array_slice($team2Key, 0, 2)
        ];
    }

    /**
     * Calcule la confiance des prédictions
     */
    private function calculatePredictionConfidence(array $team1, array $team2): int
    {
        $eloDiff = abs($team1['averageElo'] - $team2['averageElo']);
        $kdDiff = abs($team1['averageKD'] - $team2['averageKD']);
        
        $confidence = min(($eloDiff / 20) + ($kdDiff * 30) + 50, 95);
        return max(round($confidence), 60); // Minimum 60% de confiance
    }

    /**
     * Génère le raisonnement pour la prédiction du gagnant
     */
    private function generateWinnerReasoning(array $team1, array $team2, string $winner): string
    {
        $winningTeam = $winner === 'team1' ? $team1 : $team2;
        $losingTeam = $winner === 'team1' ? $team2 : $team1;

        $reasons = [];

        if ($winningTeam['averageElo'] > $losingTeam['averageElo']) {
            $diff = $winningTeam['averageElo'] - $losingTeam['averageElo'];
            $reasons[] = "ELO supérieur de {$diff} points";
        }

        if ($winningTeam['averageKD'] > $losingTeam['averageKD']) {
            $diff = round($winningTeam['averageKD'] - $losingTeam['averageKD'], 2);
            $reasons[] = "K/D supérieur de {$diff}";
        }

        if ($winningTeam['averageWinRate'] > $losingTeam['averageWinRate']) {
            $diff = round($winningTeam['averageWinRate'] - $losingTeam['averageWinRate'], 1);
            $reasons[] = "Taux de victoire supérieur de {$diff}%";
        }

        return implode(', ', $reasons) ?: 'Équipe légèrement favorisée';
    }

    /**
     * Nettoie le nom d'une carte
     */
    private function getCleanMapName(string $mapLabel): string
    {
        $mapName = str_replace('de_', '', strtolower($mapLabel));
        return ucfirst($mapName);
    }
}