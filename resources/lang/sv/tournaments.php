<?php
return [
    'title' => 'CS2-turneringar - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2-turneringar',
        'subtitle' => 'Upptäck officiella CS2-turneringar på FACEIT, följ de bästa esport-evenemangen i realtid',
        'features' => [
            'ongoing' => 'Pågående turneringar',
            'upcoming' => 'Kommande evenemang',
            'premium' => 'Premium-turneringar',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'past' => 'Avslutade',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Sök turneringar...',
            'button' => 'Sök',
        ],
        'stats' => [
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'prize_pools' => 'Prispooler',
            'participants' => 'Deltagare',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'finished' => 'Avslutad',
            'cancelled' => 'Inställd',
        ],
        'info' => [
            'participants' => 'Deltagare',
            'prize_pool' => 'Prispool',
            'registrations' => 'Registreringar',
            'organizer' => 'Arrangör',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Nivå',
            'slots' => 'Platser',
        ],
        'actions' => [
            'details' => 'Detaljer',
            'view_faceit' => 'Visa på FACEIT',
            'view_matches' => 'Visa matcher',
            'results' => 'Resultat',
            'close' => 'Stäng',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Laddar detaljer...',
            'subtitle' => 'Hämtar turneringsinformation',
        ],
        'error' => [
            'title' => 'Laddningsfel',
            'subtitle' => 'Kunde inte ladda turneringsdetaljer',
        ],
        'sections' => [
            'description' => 'Beskrivning',
            'information' => 'Information',
            'matches' => 'Turneringsmatcher',
            'results' => 'Turneringsresultat',
            'default_description' => 'Denna turnering är en del av officiella CS2-tävlingar arrangerade av FACEIT.',
        ],
        'matches' => [
            'loading' => 'Laddar matcher...',
            'no_matches' => 'Inga tillgängliga matcher för denna turnering',
            'error' => 'Fel vid laddning av matcher',
            'status' => [
                'finished' => 'Avslutad',
                'ongoing' => 'Pågående',
                'upcoming' => 'Kommande',
            ]
        ],
        'results' => [
            'loading' => 'Laddar resultat...',
            'no_results' => 'Inga tillgängliga resultat för denna turnering',
            'error' => 'Fel vid laddning av resultat',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Föregående',
        'next' => 'Nästa',
        'page' => 'Sida',
    ],
    'empty_state' => [
        'title' => 'Inga turneringar hittades',
        'subtitle' => 'Försök ändra dina filter eller sök efter något annat',
        'reset_button' => 'Återställ filter',
    ],
    'errors' => [
        'search' => 'Sökfel',
        'loading' => 'Fel vid laddning av turneringar',
        'api' => 'API-fel',
        'network' => 'Anslutningsfel',
    ]
];
