<?php
return [
    'title' => 'Faceit Scope - Analysera din FACEIT-statistik',
    'hero' => [
        'subtitle' => 'Analysera din prestanda på FACEIT med avancerade algoritmer och artificiell intelligens. Upptäck dina styrkor och förbättra dina färdigheter.',
        'features' => [
            'detailed_stats' => 'Detaljerad statistik',
            'artificial_intelligence' => 'Artificiell intelligens',
            'predictive_analysis' => 'Prediktiv analys',
        ]
    ],
    'search' => [
        'title' => 'Börja analysera',
        'subtitle' => 'Sök efter en spelare eller analysera en match för att upptäcka detaljerade insikter',
        'player' => [
            'title' => 'Sök spelare',
            'description' => 'Analysera spelarprestanda',
            'placeholder' => 'FACEIT spelarnamn...',
            'button' => 'Sök',
            'loading' => 'Söker...',
        ],
        'match' => [
            'title' => 'Analysera match',
            'description' => 'AI-förutsägelser och djupanalys',
            'placeholder' => 'Match-ID eller URL...',
            'button' => 'Analysera',
            'loading' => 'Analyserar...',
        ],
        'errors' => [
            'empty_player' => 'Ange ett spelarnamn',
            'empty_match' => 'Ange ett match-ID eller URL',
            'player_not_found' => 'Spelare ":player" hittades inte på FACEIT',
            'no_cs_stats' => 'Spelare ":player" har aldrig spelat CS2/CS:GO på FACEIT',
            'no_stats_available' => 'Ingen tillgänglig statistik för ":player"',
            'match_not_found' => 'Ingen match hittades för detta ID eller URL',
            'invalid_format' => 'Ogiltigt format för match-ID eller URL. Giltiga exempel:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'För många förfrågningar. Vänta.',
            'access_forbidden' => 'Åtkomst nekad. API-nyckelproblem.',
            'generic_player' => 'Fel vid sökning av ":player". Kontrollera anslutningen.',
            'generic_match' => 'Fel vid hämtning av match. Kontrollera ID eller URL.',
        ]
    ],
    'features' => [
        'title' => 'Funktioner',
        'subtitle' => 'Kraftfulla verktyg för att analysera och förbättra din prestanda',
        'advanced_stats' => [
            'title' => 'Avancerad statistik',
            'description' => 'Analysera din prestanda per karta, spåra ditt K/D, headshots och upptäck dina bästa/sämsta kartor med våra algoritmer.',
        ],
        'ai' => [
            'title' => 'Artificiell intelligens',
            'description' => 'Matchförutsägelser, nyckelspelareidentifiering, rollanalys och personaliserade rekommendationer baserade på din data.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobbyanalys',
            'description' => 'Upptäck matchsammansättning, styrkor och få detaljerade matchresultatförutsägelser.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Hur det fungerar',
        'subtitle' => 'Vetenskapligt tillvägagångssätt för FACEIT-prestandaanalys',
        'steps' => [
            'data_collection' => [
                'title' => 'Datainsamling',
                'description' => 'Vi använder endast det officiella FACEIT API:et för att få all din statistik på ett transparent och lagligt sätt.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algoritmisk analys',
                'description' => 'Våra algoritmer analyserar din data genom normalisering, viktning och säkerhetsberäkningar för precisa insikter.',
            ],
            'personalized_insights' => [
                'title' => 'Personaliserade insikter',
                'description' => 'Få detaljerade analyser, förutsägelser och rekommendationer för att förbättra din spelprestanda.',
            ]
        ]
    ]
];
