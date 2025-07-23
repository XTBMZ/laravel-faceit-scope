<?php
return [
    'title' => 'Spielervergleich - Faceit Scope',
    'hero' => [
        'title' => 'Spielervergleich',
        'subtitle' => 'Vergleiche die Leistung zweier CS2-Spieler',
    ],
    'search' => [
        'player1' => 'Spieler 1',
        'player2' => 'Spieler 2',
        'placeholder' => 'Faceit-Nickname...',
        'button' => 'Vergleich starten',
        'loading' => 'Analyse läuft',
        'loading_text' => 'Spielervergleich',
        'errors' => [
            'both_players' => 'Bitte gib beide Nicknames ein',
            'different_players' => 'Bitte gib zwei verschiedene Nicknames ein',
        ]
    ],
    'loading' => [
        'title' => 'Analyse läuft',
        'messages' => [
            'player1_data' => 'Daten von Spieler 1 abrufen',
            'player2_data' => 'Daten von Spieler 2 abrufen',
            'analyzing_stats' => 'Statistiken analysieren',
            'calculating_scores' => 'Leistungsscores berechnen',
            'comparing_roles' => 'Spielrollen vergleichen',
            'generating_report' => 'Abschlussbericht erstellen'
        ]
    ],
    'tabs' => [
        'overview' => 'Übersicht',
        'detailed' => 'Detaillierte Statistiken',
        'maps' => 'Karten'
    ],
    'winner' => [
        'analysis_complete' => 'Vollständige Analyse Abgeschlossen',
        'wins_analysis' => ':winner gewinnt die KI-Analyse',
        'confidence' => 'Vertrauen: :percentage%',
        'performance_score' => 'Leistungsscore',
        'matches' => 'Matches'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Leistungsscores',
            'elo_impact' => 'ELO-Einfluss',
            'combat_performance' => 'Kampfleistung',
            'experience' => 'Erfahrung',
            'advanced_stats' => 'Erweiterte Statistiken'
        ],
        'key_stats' => [
            'title' => 'Schlüsselstatistiken',
            'kd_ratio' => 'K/D-Verhältnis',
            'win_rate' => 'Gewinnrate',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry-Erfolg',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Wie werden die Scores berechnet?',
            'elo_impact' => [
                'title' => 'ELO-Einfluss (35%)',
                'description' => 'Das ELO-Level ist der wichtigste Faktor, da es direkt das Spielniveau gegen gleichstarke Gegner widerspiegelt.'
            ],
            'combat_performance' => [
                'title' => 'Kampfleistung (25%)',
                'description' => 'Kombiniert K/D, Gewinnrate, ADR und Faceit-Level zur Bewertung der Kampfeffektivität.'
            ],
            'experience' => [
                'title' => 'Erfahrung (20%)',
                'description' => 'Anzahl gespielter Matches mit Multiplikator basierend auf gesammelter Erfahrung.'
            ],
            'advanced_stats' => [
                'title' => 'Erweiterte Statistiken (20%)',
                'description' => 'Headshots, Entry-Fragging und Clutch-Fähigkeiten für tiefgreifende Spielstil-Analyse.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Allgemeine Leistung',
                'stats' => [
                    'total_matches' => 'Matches gesamt',
                    'win_rate' => 'Gewinnrate',
                    'wins' => 'Siege',
                    'avg_kd' => 'Durchschnittliches K/D-Verhältnis',
                    'adr' => 'ADR (Schaden/Runde)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Kampf und Präzision',
                'stats' => [
                    'avg_headshots' => 'Durchschnittliche Headshots',
                    'total_headshots' => 'Headshots gesamt',
                    'total_kills' => 'Kills (erweiterte Statistiken)',
                    'total_damage' => 'Schaden gesamt'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry Fragging',
                'stats' => [
                    'entry_rate' => 'Entry-Rate',
                    'entry_success' => 'Entry-Erfolgsrate',
                    'total_entries' => 'Versuche gesamt',
                    'successful_entries' => 'Erfolgreiche Entries'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch-Situationen',
                'stats' => [
                    '1v1_win_rate' => '1v1-Gewinnrate',
                    '1v2_win_rate' => '1v2-Gewinnrate',
                    '1v1_situations' => '1v1-Situationen',
                    '1v1_wins' => '1v1-Siege',
                    '1v2_situations' => '1v2-Situationen',
                    '1v2_wins' => '1v2-Siege'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility und Support',
                'stats' => [
                    'flash_success' => 'Flash-Erfolgsrate',
                    'flashes_per_round' => 'Flashes pro Runde',
                    'total_flashes' => 'Flashes gesamt',
                    'successful_flashes' => 'Erfolgreiche Flashes',
                    'enemies_flashed_per_round' => 'Geblendete Gegner/Runde',
                    'total_enemies_flashed' => 'Geblendete Gegner gesamt',
                    'utility_success' => 'Utility-Erfolgsrate',
                    'utility_damage_per_round' => 'Utility-Schaden/Runde',
                    'total_utility_damage' => 'Utility-Schaden gesamt'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper und Spezialwaffen',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper-Kill-Rate',
                    'sniper_kills_per_round' => 'Sniper-Kills/Runde',
                    'total_sniper_kills' => 'Sniper-Kills gesamt'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Serien und Konstanz',
                'stats' => [
                    'current_streak' => 'Aktuelle Serie',
                    'longest_streak' => 'Längste Serie'
                ]
            ]
        ],
        'legend' => 'Grüne Werte zeigen den Spieler mit der besseren Leistung für jede Statistik'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Keine gemeinsamen Karten',
            'description' => 'Beide Spieler haben keine gemeinsamen Karten mit ausreichenden Daten.'
        ],
        'dominates' => ':player dominiert',
        'win_rate' => 'Gewinnrate (:matches Matches)',
        'kd_ratio' => 'K/D-Verhältnis',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Karten-Zusammenfassung',
            'maps_dominated' => 'Dominierte Karten',
            'best_map' => 'Beste Karte',
            'none' => 'Keine'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Spezialisiert auf Site-Entries'
        ],
        'support' => [
            'name' => 'Support',
            'description' => 'Meister der Team-Utilities'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Experte in schwierigen Situationen'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Eliminierungs-Spezialist'
        ],
        'versatile' => [
            'name' => 'Vielseitig',
            'description' => 'Ausgewogener Spieler'
        ]
    ],
    'error' => [
        'title' => 'Fehler',
        'default_message' => 'Ein Fehler ist während des Vergleichs aufgetreten',
        'retry' => 'Erneut versuchen',
        'player_not_found' => 'Spieler ":player" nicht gefunden',
        'stats_error' => 'Fehler beim Abrufen der Statistiken: :status'
    ]
];
