<?php
return [
    'title' => 'Pelaajien vertailu - Faceit Scope',
    'hero' => [
        'title' => 'Pelaajien vertailu',
        'subtitle' => 'Vertaa kahden CS2-pelaajan suorituskykyä',
    ],
    'search' => [
        'player1' => 'Pelaaja 1',
        'player2' => 'Pelaaja 2',
        'placeholder' => 'Faceit käyttäjänimi...',
        'button' => 'Aloita vertailu',
        'loading' => 'Analysoidaan',
        'loading_text' => 'Vertaillaan pelaajia',
        'errors' => [
            'both_players' => 'Anna kaksi käyttäjänimeä',
            'different_players' => 'Anna kaksi eri käyttäjänimeä',
        ]
    ],
    'loading' => [
        'title' => 'Analysoidaan',
        'messages' => [
            'player1_data' => 'Haetaan pelaaja 1:n tietoja',
            'player2_data' => 'Haetaan pelaaja 2:n tietoja',
            'analyzing_stats' => 'Analysoidaan tilastoja',
            'calculating_scores' => 'Lasketaan suorituspisteitä',
            'comparing_roles' => 'Vertaillaan pelirooleja',
            'generating_report' => 'Luodaan lopullinen raportti'
        ]
    ],
    'tabs' => [
        'overview' => 'Yleiskatsaus',
        'detailed' => 'Yksityiskohtaiset tilastot',
        'maps' => 'Kartat'
    ],
    'winner' => [
        'analysis_complete' => 'Analyysi valmis',
        'wins_analysis' => ':winner voittaa AI-analyysin',
        'confidence' => 'Luottamustaso: :percentage%',
        'performance_score' => 'Suorituspisteet',
        'matches' => 'Ottelut'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Suorituspisteet',
            'elo_impact' => 'ELO-vaikutus',
            'combat_performance' => 'Taistelusuoritus',
            'experience' => 'Kokemus',
            'advanced_stats' => 'Edistyneet tilastot'
        ],
        'key_stats' => [
            'title' => 'Avainfaktiot',
            'kd_ratio' => 'K/D-suhde',
            'win_rate' => 'Voittoprosentti',
            'headshots' => 'Pääosumit',
            'adr' => 'ADR',
            'entry_success' => 'Entry-onnistuminen',
            'clutch_1v1' => '1v1 clutch'
        ],
        'calculation_info' => [
            'title' => 'Miten pisteet lasketaan?',
            'elo_impact' => [
                'title' => 'ELO-vaikutus (35%)',
                'description' => 'ELO-taso on tärkein tekijä, koska se heijastaa suoraan pelitasoa samanarvoisia vastustajia vastaan.'
            ],
            'combat_performance' => [
                'title' => 'Taistelusuoritus (25%)',
                'description' => 'Yhdistää K/D:n, voittoprosentin, ADR:n ja Faceit-tason taistelun tehokkuuden arvioimiseksi.'
            ],
            'experience' => [
                'title' => 'Kokemus (20%)',
                'description' => 'Pelattujen otteluiden määrä, kerroin kertyneeseen kokemukseen perustuen.'
            ],
            'advanced_stats' => [
                'title' => 'Edistyneet tilastot (20%)',
                'description' => 'Pääosumit, entry- ja clutch-kyvyt syvällistä pelityylin analyysiä varten.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Yleinen suoritus',
                'stats' => [
                    'total_matches' => 'Ottelut yhteensä',
                    'win_rate' => 'Voittoprosentti',
                    'wins' => 'Voitot',
                    'avg_kd' => 'Keskim. K/D-suhde',
                    'adr' => 'ADR (Vahinko/kierros)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Taistelu ja tarkkuus',
                'stats' => [
                    'avg_headshots' => 'Keskim. pääosumit',
                    'total_headshots' => 'Pääosumit yhteensä',
                    'total_kills' => 'Tapot (laajennetut tilastot)',
                    'total_damage' => 'Kokonaisvahinko'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Entry-prosentti',
                    'entry_success' => 'Entry-onnistumisprosentti',
                    'total_entries' => 'Yritykset yhteensä',
                    'successful_entries' => 'Onnistuneet entryt'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch-tilanteet',
                'stats' => [
                    '1v1_win_rate' => '1v1 voittoprosentti',
                    '1v2_win_rate' => '1v2 voittoprosentti',
                    '1v1_situations' => '1v1 tilanteet',
                    '1v1_wins' => '1v1 voitot',
                    '1v2_situations' => '1v2 tilanteet',
                    '1v2_wins' => '1v2 voitot'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility ja tuki',
                'stats' => [
                    'flash_success' => 'Flash-onnistumisprosentti',
                    'flashes_per_round' => 'Flashit/kierros',
                    'total_flashes' => 'Flashit yhteensä',
                    'successful_flashes' => 'Onnistuneet flashit',
                    'enemies_flashed_per_round' => 'Vihollisia flashattu/kierros',
                    'total_enemies_flashed' => 'Vihollisia flashattu yhteensä',
                    'utility_success' => 'Utility-onnistumisprosentti',
                    'utility_damage_per_round' => 'Utility-vahinko/kierros',
                    'total_utility_damage' => 'Utility-vahinko yhteensä'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper ja erikoisaseet',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper-tappoprosentti',
                    'sniper_kills_per_round' => 'Sniper-tapot/kierros',
                    'total_sniper_kills' => 'Sniper-tapot yhteensä'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Putket ja johdonmukaisuus',
                'stats' => [
                    'current_streak' => 'Nykyinen putki',
                    'longest_streak' => 'Pisin putki'
                ]
            ]
        ],
        'legend' => 'Vihreät arvot osoittavat pelaajan, joka suoriutuu paremmin kyseisessä tilastossa'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Ei yhteisiä karttoja',
            'description' => 'Kahdella pelaajalla ei ole yhteisiä karttoja, joilla olisi riittävästi dataa.'
        ],
        'dominates' => ':player dominoi',
        'win_rate' => 'Voittoprosentti (:matches ottelua)',
        'kd_ratio' => 'K/D-suhde',
        'headshots' => 'Pääosumit',
        'adr' => 'ADR',
        'mvps' => 'MVP:t',
        'summary' => [
            'title' => 'Karttojen yhteenveto',
            'maps_dominated' => 'Dominoidut kartat',
            'best_map' => 'Paras kartta',
            'none' => 'Ei mitään'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Erikoistunut entry-hyökkäyksiin'
        ],
        'support' => [
            'name' => 'Tuki',
            'description' => 'Joukkueen utility-mestari'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Vaikeiden tilanteiden asiantuntija'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Eliminaatio-asiantuntija'
        ],
        'versatile' => [
            'name' => 'Monipuolinen',
            'description' => 'Tasapainoinen pelaaja'
        ]
    ],
    'error' => [
        'title' => 'Virhe',
        'default_message' => 'Vertailussa tapahtui virhe',
        'retry' => 'Yritä uudelleen',
        'player_not_found' => 'Pelaajaa ":player" ei löytynyt',
        'stats_error' => 'Virhe tilastojen haussa: :status'
    ]
];
