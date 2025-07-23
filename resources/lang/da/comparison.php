<?php
return [
    'title' => 'Spiller-sammenligning - Faceit Scope',
    'hero' => [
        'title' => 'Spiller-sammenligning',
        'subtitle' => 'Sammenlign to CS2-spilleres præstation',
    ],
    'search' => [
        'player1' => 'Spiller 1',
        'player2' => 'Spiller 2',
        'placeholder' => 'Faceit brugernavn...',
        'button' => 'Start sammenligning',
        'loading' => 'Analyserer',
        'loading_text' => 'Sammenligner spillere',
        'errors' => [
            'both_players' => 'Indtast venligst to brugernavne',
            'different_players' => 'Indtast venligst to forskellige brugernavne',
        ]
    ],
    'loading' => [
        'title' => 'Analyserer',
        'messages' => [
            'player1_data' => 'Henter spiller 1 data',
            'player2_data' => 'Henter spiller 2 data',
            'analyzing_stats' => 'Analyserer statistikker',
            'calculating_scores' => 'Beregner præstations-scores',
            'comparing_roles' => 'Sammenligner spil-roller',
            'generating_report' => 'Genererer final rapport'
        ]
    ],
    'tabs' => [
        'overview' => 'Oversigt',
        'detailed' => 'Detaljerede statistikker',
        'maps' => 'Kort'
    ],
    'winner' => [
        'analysis_complete' => 'Analyse fuldført',
        'wins_analysis' => ':winner vinder AI-analysen',
        'confidence' => 'Konfidensgrad: :percentage%',
        'performance_score' => 'Præstations-score',
        'matches' => 'Kampe'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Præstations-scores',
            'elo_impact' => 'ELO påvirkning',
            'combat_performance' => 'Kamp-præstation',
            'experience' => 'Erfaring',
            'advanced_stats' => 'Avancerede statistikker'
        ],
        'key_stats' => [
            'title' => 'Nøgle-statistikker',
            'kd_ratio' => 'K/D-forhold',
            'win_rate' => 'Vindrate',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry succes',
            'clutch_1v1' => '1v1 clutch'
        ],
        'calculation_info' => [
            'title' => 'Hvordan beregnes scoren?',
            'elo_impact' => [
                'title' => 'ELO påvirkning (35%)',
                'description' => 'ELO-niveauet er den vigtigste faktor, da det direkte afspejler spilleniveauet mod modstandere af samme styrke.'
            ],
            'combat_performance' => [
                'title' => 'Kamp-præstation (25%)',
                'description' => 'Kombinerer K/D, vindrate, ADR og Faceit-niveau for at vurdere kamp-effektivitet.'
            ],
            'experience' => [
                'title' => 'Erfaring (20%)',
                'description' => 'Antal kampe spillet, multiplikator baseret på akkumuleret erfaring.'
            ],
            'advanced_stats' => [
                'title' => 'Avancerede statistikker (20%)',
                'description' => 'Headshots, entry og clutch-evner for dybdegående spilstil-analyse.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Generel præstation',
                'stats' => [
                    'total_matches' => 'Total kampe',
                    'win_rate' => 'Vindrate',
                    'wins' => 'Sejre',
                    'avg_kd' => 'Gns. K/D-forhold',
                    'adr' => 'ADR (Skade/runde)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Kamp og præcision',
                'stats' => [
                    'avg_headshots' => 'Gns. headshots',
                    'total_headshots' => 'Total headshots',
                    'total_kills' => 'Kills (extended stats)',
                    'total_damage' => 'Total skade'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Entry rate',
                    'entry_success' => 'Entry succes-rate',
                    'total_entries' => 'Total forsøg',
                    'successful_entries' => 'Succesfulde entries'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch situationer',
                'stats' => [
                    '1v1_win_rate' => '1v1 vindrate',
                    '1v2_win_rate' => '1v2 vindrate',
                    '1v1_situations' => '1v1 situationer',
                    '1v1_wins' => '1v1 sejre',
                    '1v2_situations' => '1v2 situationer',
                    '1v2_wins' => '1v2 sejre'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility og support',
                'stats' => [
                    'flash_success' => 'Flash succes-rate',
                    'flashes_per_round' => 'Flash/runde',
                    'total_flashes' => 'Total flash',
                    'successful_flashes' => 'Succesfulde flash',
                    'enemies_flashed_per_round' => 'Fjender flashet/runde',
                    'total_enemies_flashed' => 'Total fjender flashet',
                    'utility_success' => 'Utility succes-rate',
                    'utility_damage_per_round' => 'Utility skade/runde',
                    'total_utility_damage' => 'Total utility skade'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper og special våben',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper kill rate',
                    'sniper_kills_per_round' => 'Sniper kills/runde',
                    'total_sniper_kills' => 'Total sniper kills'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Streaks og konsistens',
                'stats' => [
                    'current_streak' => 'Nuværende streak',
                    'longest_streak' => 'Længste streak'
                ]
            ]
        ],
        'legend' => 'Grønne værdier indikerer den spiller, der præsterer bedre i den statistik'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Ingen fælles kort',
            'description' => 'De to spillere har ingen fælles kort med tilstrækkelig data.'
        ],
        'dominates' => ':player dominerer',
        'win_rate' => 'Vindrate (:matches kampe)',
        'kd_ratio' => 'K/D-forhold',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Kort-sammenfatning',
            'maps_dominated' => 'Kort domineret',
            'best_map' => 'Bedste kort',
            'none' => 'Ingen'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specialiseret i entry-point angreb'
        ],
        'support' => [
            'name' => 'Support',
            'description' => 'Hold utility-mester'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Vanskelige situationer ekspert'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Elimination ekspert'
        ],
        'versatile' => [
            'name' => 'Alsidig',
            'description' => 'Balanceret spiller'
        ]
    ],
    'error' => [
        'title' => 'Fejl',
        'default_message' => 'Der opstod en fejl under sammenligningen',
        'retry' => 'Prøv igen',
        'player_not_found' => 'Spiller ":player" ikke fundet',
        'stats_error' => 'Fejl ved hentning af statistikker: :status'
    ]
];
