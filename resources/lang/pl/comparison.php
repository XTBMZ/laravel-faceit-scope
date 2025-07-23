<?php
return [
    'title' => 'Porównanie graczy - Faceit Scope',
    'hero' => [
        'title' => 'Porównanie graczy',
        'subtitle' => 'Porównaj wydajność dwóch graczy CS2',
    ],
    'search' => [
        'player1' => 'Gracz 1',
        'player2' => 'Gracz 2',
        'placeholder' => 'Nickname Faceit...',
        'button' => 'Rozpocznij porównanie',
        'loading' => 'Analizowanie',
        'loading_text' => 'Porównywanie graczy',
        'errors' => [
            'both_players' => 'Wprowadź dwa nicknames',
            'different_players' => 'Wprowadź dwa różne nicknames',
        ]
    ],
    'loading' => [
        'title' => 'Analizowanie',
        'messages' => [
            'player1_data' => 'Pobieranie danych gracza 1',
            'player2_data' => 'Pobieranie danych gracza 2',
            'analyzing_stats' => 'Analizowanie statystyk',
            'calculating_scores' => 'Obliczanie wyników wydajności',
            'comparing_roles' => 'Porównywanie ról w grze',
            'generating_report' => 'Generowanie końcowego raportu'
        ]
    ],
    'tabs' => [
        'overview' => 'Przegląd',
        'detailed' => 'Szczegółowe statystyki',
        'maps' => 'Mapy'
    ],
    'winner' => [
        'analysis_complete' => 'Analiza zakończona',
        'wins_analysis' => ':winner wygrywa analizę AI',
        'confidence' => 'Pewność: :percentage%',
        'performance_score' => 'Wynik wydajności',
        'matches' => 'Mecze'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Wyniki wydajności',
            'elo_impact' => 'Wpływ ELO',
            'combat_performance' => 'Wydajność bojowa',
            'experience' => 'Doświadczenie',
            'advanced_stats' => 'Zaawansowane statystyki'
        ],
        'key_stats' => [
            'title' => 'Kluczowe statystyki',
            'kd_ratio' => 'Stosunek K/D',
            'win_rate' => 'Wskaźnik wygranych',
            'headshots' => 'Headshot',
            'adr' => 'ADR',
            'entry_success' => 'Sukces entry',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Jak obliczane są wyniki?',
            'elo_impact' => [
                'title' => 'Wpływ ELO (35%)',
                'description' => 'Poziom ELO to najważniejszy czynnik, ponieważ bezpośrednio odzwierciedla poziom gry przeciwko przeciwnikom o równej sile.'
            ],
            'combat_performance' => [
                'title' => 'Wydajność bojowa (25%)',
                'description' => 'Łączy K/D, wskaźnik wygranych, ADR i poziom Faceit do oceny skuteczności bojowej.'
            ],
            'experience' => [
                'title' => 'Doświadczenie (20%)',
                'description' => 'Liczba rozegranych meczów, mnożnik oparty na skumulowanym doświadczeniu.'
            ],
            'advanced_stats' => [
                'title' => 'Zaawansowane statystyki (20%)',
                'description' => 'Headshot, umiejętności entry i clutch do dogłębnej analizy stylu gry.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Wydajność ogólna',
                'stats' => [
                    'total_matches' => 'Łączne mecze',
                    'win_rate' => 'Wskaźnik wygranych',
                    'wins' => 'Zwycięstwa',
                    'avg_kd' => 'Średni stosunek K/D',
                    'adr' => 'ADR (Obrażenia na rundę)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Walka i precyzja',
                'stats' => [
                    'avg_headshots' => 'Średnie headshot',
                    'total_headshots' => 'Łączne headshot',
                    'total_kills' => 'Zabójstwa (rozszerzone statystyki)',
                    'total_damage' => 'Łączne obrażenia'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Wskaźnik entry',
                    'entry_success' => 'Sukces entry',
                    'total_entries' => 'Łączne próby',
                    'successful_entries' => 'Udane entry'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Sytuacje clutch',
                'stats' => [
                    '1v1_win_rate' => 'Wskaźnik wygranych 1v1',
                    '1v2_win_rate' => 'Wskaźnik wygranych 1v2',
                    '1v1_situations' => 'Sytuacje 1v1',
                    '1v1_wins' => 'Wygrane 1v1',
                    '1v2_situations' => 'Sytuacje 1v2',
                    '1v2_wins' => 'Wygrane 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility i wsparcie',
                'stats' => [
                    'flash_success' => 'Sukces flash',
                    'flashes_per_round' => 'Flash na rundę',
                    'total_flashes' => 'Łączne flash',
                    'successful_flashes' => 'Udane flash',
                    'enemies_flashed_per_round' => 'Oślepieni wrogowie na rundę',
                    'total_enemies_flashed' => 'Łączni oślepieni wrogowie',
                    'utility_success' => 'Sukces utility',
                    'utility_damage_per_round' => 'Obrażenia utility na rundę',
                    'total_utility_damage' => 'Łączne obrażenia utility'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper i broń specjalna',
                'stats' => [
                    'sniper_kill_rate' => 'Wskaźnik zabójstw sniper',
                    'sniper_kills_per_round' => 'Zabójstwa sniper na rundę',
                    'total_sniper_kills' => 'Łączne zabójstwa sniper'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Passy i spójność',
                'stats' => [
                    'current_streak' => 'Aktualna passa',
                    'longest_streak' => 'Najdłuższa passa'
                ]
            ]
        ],
        'legend' => 'Wartości w kolorze zielonym wskazują gracza, który lepiej radzi sobie w tej statystyce'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Brak wspólnych map',
            'description' => 'Dwaj gracze nie mają wspólnych map z wystarczającymi danymi.'
        ],
        'dominates' => ':player dominuje',
        'win_rate' => 'Wskaźnik wygranych (:matches meczów)',
        'kd_ratio' => 'Stosunek K/D',
        'headshots' => 'Headshot',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Podsumowanie map',
            'maps_dominated' => 'Zdominowane mapy',
            'best_map' => 'Najlepsza mapa',
            'none' => 'Brak'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specjalizuje się w atakowaniu pozycji'
        ],
        'support' => [
            'name' => 'Wsparcie',
            'description' => 'Mistrz wsparcia drużyny'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Ekspert w trudnych sytuacjach'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Specjalista od eliminacji'
        ],
        'versatile' => [
            'name' => 'Wszechstronny',
            'description' => 'Gracz zrównoważony'
        ]
    ],
    'error' => [
        'title' => 'Błąd',
        'default_message' => 'Wystąpił błąd podczas porównania',
        'retry' => 'Ponów',
        'player_not_found' => 'Gracz ":player" nie znaleziony',
        'stats_error' => 'Błąd pobierania statystyk: :status'
    ]
];
