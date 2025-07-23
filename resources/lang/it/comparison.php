<?php
return [
    'title' => 'Confronto Giocatori - Faceit Scope',
    'hero' => [
        'title' => 'Confronto Giocatori',
        'subtitle' => 'Confronta le prestazioni di due giocatori CS2',
    ],
    'search' => [
        'player1' => 'Giocatore 1',
        'player2' => 'Giocatore 2',
        'placeholder' => 'Nickname Faceit...',
        'button' => 'Inizia confronto',
        'loading' => 'Analisi in corso',
        'loading_text' => 'Confronto giocatori',
        'errors' => [
            'both_players' => 'Inserisci entrambi i nickname',
            'different_players' => 'Inserisci due nickname diversi',
        ]
    ],
    'loading' => [
        'title' => 'Analisi in corso',
        'messages' => [
            'player1_data' => 'Recupero dati giocatore 1',
            'player2_data' => 'Recupero dati giocatore 2',
            'analyzing_stats' => 'Analisi statistiche',
            'calculating_scores' => 'Calcolo punteggi prestazioni',
            'comparing_roles' => 'Confronto ruoli di gioco',
            'generating_report' => 'Generazione report finale'
        ]
    ],
    'tabs' => [
        'overview' => 'Panoramica',
        'detailed' => 'Statistiche dettagliate',
        'maps' => 'Mappe'
    ],
    'winner' => [
        'analysis_complete' => 'Analisi Completa Terminata',
        'wins_analysis' => ':winner vince l\'analisi IA',
        'confidence' => 'Fiducia: :percentage%',
        'performance_score' => 'Punteggio Prestazioni',
        'matches' => 'Partite'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Punteggi prestazioni',
            'elo_impact' => 'Impatto ELO',
            'combat_performance' => 'Prestazioni Combattimento',
            'experience' => 'Esperienza',
            'advanced_stats' => 'Statistiche Avanzate'
        ],
        'key_stats' => [
            'title' => 'Statistiche chiave',
            'kd_ratio' => 'Rapporto K/D',
            'win_rate' => 'Tasso di vittorie',
            'headshots' => 'Headshot',
            'adr' => 'ADR',
            'entry_success' => 'Successo Entrata',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Come sono calcolati i punteggi?',
            'elo_impact' => [
                'title' => 'Impatto ELO (35%)',
                'description' => 'Il livello ELO è il fattore più importante poiché riflette direttamente il livello di gioco contro avversari di pari forza.'
            ],
            'combat_performance' => [
                'title' => 'Prestazioni Combattimento (25%)',
                'description' => 'Combina K/D, tasso vittorie, ADR e livello Faceit per valutare l\'efficacia in combattimento.'
            ],
            'experience' => [
                'title' => 'Esperienza (20%)',
                'description' => 'Numero di partite giocate con moltiplicatore basato sull\'esperienza accumulata.'
            ],
            'advanced_stats' => [
                'title' => 'Statistiche Avanzate (20%)',
                'description' => 'Headshot, entry fragging e abilità clutch per analisi approfondita dello stile di gioco.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Prestazioni generali',
                'stats' => [
                    'total_matches' => 'Partite totali',
                    'win_rate' => 'Tasso di vittorie',
                    'wins' => 'Vittorie',
                    'avg_kd' => 'Rapporto K/D medio',
                    'adr' => 'ADR (danno/round)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Combattimento e precisione',
                'stats' => [
                    'avg_headshots' => 'Headshot medi',
                    'total_headshots' => 'Headshot totali',
                    'total_kills' => 'Kill (statistiche estese)',
                    'total_damage' => 'Danno totale'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Tasso di entrata',
                    'entry_success' => 'Tasso successo entrata',
                    'total_entries' => 'Tentativi totali',
                    'successful_entries' => 'Entrate riuscite'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Situazioni clutch',
                'stats' => [
                    '1v1_win_rate' => 'Tasso vittorie 1v1',
                    '1v2_win_rate' => 'Tasso vittorie 1v2',
                    '1v1_situations' => 'Situazioni 1v1',
                    '1v1_wins' => 'Vittorie 1v1',
                    '1v2_situations' => 'Situazioni 1v2',
                    '1v2_wins' => 'Vittorie 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utilità e supporto',
                'stats' => [
                    'flash_success' => 'Tasso successo flash',
                    'flashes_per_round' => 'Flash per round',
                    'total_flashes' => 'Flash totali',
                    'successful_flashes' => 'Flash riusciti',
                    'enemies_flashed_per_round' => 'Nemici accecati/round',
                    'total_enemies_flashed' => 'Nemici accecati totali',
                    'utility_success' => 'Tasso successo utilità',
                    'utility_damage_per_round' => 'Danno utilità/round',
                    'total_utility_damage' => 'Danno utilità totale'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper e armi speciali',
                'stats' => [
                    'sniper_kill_rate' => 'Tasso kill sniper',
                    'sniper_kills_per_round' => 'Kill sniper/round',
                    'total_sniper_kills' => 'Kill sniper totali'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Streak e consistenza',
                'stats' => [
                    'current_streak' => 'Streak attuale',
                    'longest_streak' => 'Streak più lunga'
                ]
            ]
        ],
        'legend' => 'I valori in verde indicano il giocatore con le migliori prestazioni per ogni statistica'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Nessuna mappa in comune',
            'description' => 'Entrambi i giocatori non hanno mappe in comune con dati sufficienti.'
        ],
        'dominates' => ':player domina',
        'win_rate' => 'Tasso Vittorie (:matches partite)',
        'kd_ratio' => 'Rapporto K/D',
        'headshots' => 'Headshot',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Riassunto mappe',
            'maps_dominated' => 'Mappe dominate',
            'best_map' => 'Mappa migliore',
            'none' => 'Nessuna'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specializzato in entrate nei siti'
        ],
        'support' => [
            'name' => 'Supporto',
            'description' => 'Maestro delle utilità di squadra'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Esperto in situazioni difficili'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Specialista in eliminazioni'
        ],
        'versatile' => [
            'name' => 'Versatile',
            'description' => 'Giocatore equilibrato'
        ]
    ],
    'error' => [
        'title' => 'Errore',
        'default_message' => 'Si è verificato un errore durante il confronto',
        'retry' => 'Riprova',
        'player_not_found' => 'Giocatore ":player" non trovato',
        'stats_error' => 'Errore recupero statistiche: :status'
    ]
];
