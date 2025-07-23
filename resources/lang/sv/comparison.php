<?php
return [
    'title' => 'Spelarjämförelse - Faceit Scope',
    'hero' => [
        'title' => 'Spelarjämförelse',
        'subtitle' => 'Jämför prestandan hos två CS2-spelare',
    ],
    'search' => [
        'player1' => 'Spelare 1',
        'player2' => 'Spelare 2',
        'placeholder' => 'Faceit smeknamn...',
        'button' => 'Starta jämförelse',
        'loading' => 'Analyserar',
        'loading_text' => 'Jämför spelare',
        'errors' => [
            'both_players' => 'Ange två smeknamn',
            'different_players' => 'Ange två olika smeknamn',
        ]
    ],
    'loading' => [
        'title' => 'Analyserar',
        'messages' => [
            'player1_data' => 'Hämtar data för spelare 1',
            'player2_data' => 'Hämtar data för spelare 2',
            'analyzing_stats' => 'Analyserar statistik',
            'calculating_scores' => 'Beräknar prestandapoäng',
            'comparing_roles' => 'Jämför spelroller',
            'generating_report' => 'Genererar slutrapport'
        ]
    ],
    'tabs' => [
        'overview' => 'Översikt',
        'detailed' => 'Detaljerad statistik',
        'maps' => 'Kartor'
    ],
    'winner' => [
        'analysis_complete' => 'Analys klar',
        'wins_analysis' => ':winner vinner AI-analysen',
        'confidence' => 'Säkerhet: :percentage%',
        'performance_score' => 'Prestandapoäng',
        'matches' => 'Matcher'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Prestandapoäng',
            'elo_impact' => 'ELO-påverkan',
            'combat_performance' => 'Stridsprestanda',
            'experience' => 'Erfarenhet',
            'advanced_stats' => 'Avancerad statistik'
        ],
        'key_stats' => [
            'title' => 'Nyckelstatistik',
            'kd_ratio' => 'K/D-förhållande',
            'win_rate' => 'Vinstprocent',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry-framgång',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Hur beräknas poängen?',
            'elo_impact' => [
                'title' => 'ELO-påverkan (35%)',
                'description' => 'ELO-nivån är den viktigaste faktorn eftersom den direkt återspeglar spelnivån mot motståndare av jämn styrka.'
            ],
            'combat_performance' => [
                'title' => 'Stridsprestanda (25%)',
                'description' => 'Kombinerar K/D, vinstprocent, ADR och Faceit-nivå för att bedöma stridseffektivitet.'
            ],
            'experience' => [
                'title' => 'Erfarenhet (20%)',
                'description' => 'Antal spelade matcher, multiplikator baserad på ackumulerad erfarenhet.'
            ],
            'advanced_stats' => [
                'title' => 'Avancerad statistik (20%)',
                'description' => 'Headshots, entry- och clutch-förmågor för djupgående spelstilsanalys.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Allmän prestanda',
                'stats' => [
                    'total_matches' => 'Totala matcher',
                    'win_rate' => 'Vinstprocent',
                    'wins' => 'Vinster',
                    'avg_kd' => 'Genomsnittligt K/D-förhållande',
                    'adr' => 'ADR (Skada per runda)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Strid och precision',
                'stats' => [
                    'avg_headshots' => 'Genomsnittliga headshots',
                    'total_headshots' => 'Totala headshots',
                    'total_kills' => 'Kills (utökad statistik)',
                    'total_damage' => 'Total skada'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Entry-rate',
                    'entry_success' => 'Entry-framgång',
                    'total_entries' => 'Totala försök',
                    'successful_entries' => 'Framgångsrika entries'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch-situationer',
                'stats' => [
                    '1v1_win_rate' => '1v1 vinstprocent',
                    '1v2_win_rate' => '1v2 vinstprocent',
                    '1v1_situations' => '1v1-situationer',
                    '1v1_wins' => '1v1-vinster',
                    '1v2_situations' => '1v2-situationer',
                    '1v2_wins' => '1v2-vinster'
                ]
            ],
            'utility_support' => [
                'title' => 'Verktyg och stöd',
                'stats' => [
                    'flash_success' => 'Flash-framgång',
                    'flashes_per_round' => 'Flash per runda',
                    'total_flashes' => 'Totala flash',
                    'successful_flashes' => 'Framgångsrika flash',
                    'enemies_flashed_per_round' => 'Fiender flashade per runda',
                    'total_enemies_flashed' => 'Totala fiender flashade',
                    'utility_success' => 'Verktygsframgång',
                    'utility_damage_per_round' => 'Verktygsskada per runda',
                    'total_utility_damage' => 'Total verktygsskada'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper och specialvapen',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper-kill-rate',
                    'sniper_kills_per_round' => 'Sniper-kills per runda',
                    'total_sniper_kills' => 'Totala sniper-kills'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Streaks och konsistens',
                'stats' => [
                    'current_streak' => 'Nuvarande streak',
                    'longest_streak' => 'Längsta streak'
                ]
            ]
        ],
        'legend' => 'Gröna värden indikerar spelaren som presterar bättre i den statistiken'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Inga gemensamma kartor',
            'description' => 'De två spelarna har inga gemensamma kartor med tillräcklig data.'
        ],
        'dominates' => ':player dominerar',
        'win_rate' => 'Vinstprocent (:matches matcher)',
        'kd_ratio' => 'K/D-förhållande',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Kartsammanfattning',
            'maps_dominated' => 'Dominerade kartor',
            'best_map' => 'Bästa karta',
            'none' => 'Ingen'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specialiserad på att attackera positioner'
        ],
        'support' => [
            'name' => 'Stöd',
            'description' => 'Lagstödsmästare'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Expert på svåra situationer'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Elimineringsspecialist'
        ],
        'versatile' => [
            'name' => 'Mångsidig',
            'description' => 'Balanserad spelare'
        ]
    ],
    'error' => [
        'title' => 'Fel',
        'default_message' => 'Ett fel uppstod under jämförelsen',
        'retry' => 'Försök igen',
        'player_not_found' => 'Spelare ":player" hittades inte',
        'stats_error' => 'Fel vid hämtning av statistik: :status'
    ]
];
