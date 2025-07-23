<?php
return [
    'title' => 'CS2 turneringer - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2 turneringer',
        'subtitle' => 'Opdag officielle FACEIT CS2 turneringer, følg de bedste esport-begivenheder live',
        'features' => [
            'ongoing' => 'Igangværende turneringer',
            'upcoming' => 'Kommende begivenheder',
            'premium' => 'Premium turneringer',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Igangværende',
            'upcoming' => 'Kommende',
            'past' => 'Afsluttede',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Søg turneringer...',
            'button' => 'Søg',
        ],
        'stats' => [
            'ongoing' => 'Igangværende',
            'upcoming' => 'Kommende',
            'prize_pools' => 'Præmie-puljer',
            'participants' => 'Deltagere',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Igangværende',
            'upcoming' => 'Kommende',
            'finished' => 'Afsluttet',
            'cancelled' => 'Aflyst',
        ],
        'info' => [
            'participants' => 'Deltagere',
            'prize_pool' => 'Præmie-pulje',
            'registrations' => 'Tilmeldinger',
            'organizer' => 'Arrangør',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Niveau',
            'slots' => 'Pladser',
        ],
        'actions' => [
            'details' => 'Detaljer',
            'view_faceit' => 'Se på FACEIT',
            'view_matches' => 'Se kampe',
            'results' => 'Resultater',
            'close' => 'Luk',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Indlæser detaljer...',
            'subtitle' => 'Henter turnerings-information',
        ],
        'error' => [
            'title' => 'Indlæsnings fejl',
            'subtitle' => 'Kunne ikke indlæse turnerings-detaljer',
        ],
        'sections' => [
            'description' => 'Beskrivelse',
            'information' => 'Information',
            'matches' => 'Turnerings-kampe',
            'results' => 'Turnerings-resultater',
            'default_description' => 'Denne turnering er en del af officiel CS2-konkurrence organiseret af FACEIT.',
        ],
        'matches' => [
            'loading' => 'Indlæser kampe...',
            'no_matches' => 'Ingen kampe tilgængelige for denne turnering',
            'error' => 'Fejl ved indlæsning af kampe',
            'status' => [
                'finished' => 'Afsluttet',
                'ongoing' => 'Igangværende',
                'upcoming' => 'Kommende',
            ]
        ],
        'results' => [
            'loading' => 'Indlæser resultater...',
            'no_results' => 'Ingen resultater tilgængelige for denne turnering',
            'error' => 'Fejl ved indlæsning af resultater',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Forrige',
        'next' => 'Næste',
        'page' => 'Side',
    ],
    'empty_state' => [
        'title' => 'Ingen turneringer fundet',
        'subtitle' => 'Prøv at ændre dine filtre eller søg efter noget andet',
        'reset_button' => 'Nulstil filtre',
    ],
    'errors' => [
        'search' => 'Søgefejl',
        'loading' => 'Fejl ved indlæsning af turneringer',
        'api' => 'API fejl',
        'network' => 'Forbindelses fejl',
    ]
];
