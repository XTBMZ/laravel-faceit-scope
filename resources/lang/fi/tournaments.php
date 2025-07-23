<?php
return [
    'title' => 'CS2 turnaukset - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2 turnaukset',
        'subtitle' => 'Löydä viralliset FACEIT CS2 -turnaukset, seuraa parhaita esports-tapahtumia livenä',
        'features' => [
            'ongoing' => 'Käynnissä olevat turnaukset',
            'upcoming' => 'Tulevat tapahtumat',
            'premium' => 'Premium-turnaukset',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Käynnissä',
            'upcoming' => 'Tulossa',
            'past' => 'Päättyneet',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Hae turnauksia...',
            'button' => 'Hae',
        ],
        'stats' => [
            'ongoing' => 'Käynnissä',
            'upcoming' => 'Tulossa',
            'prize_pools' => 'Palkintopotit',
            'participants' => 'Osallistujat',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Käynnissä',
            'upcoming' => 'Tulossa',
            'finished' => 'Päättynyt',
            'cancelled' => 'Peruttu',
        ],
        'info' => [
            'participants' => 'Osallistujat',
            'prize_pool' => 'Palkintopotti',
            'registrations' => 'Ilmoittautumiset',
            'organizer' => 'Järjestäjä',
            'status' => 'Tila',
            'region' => 'Alue',
            'level' => 'Taso',
            'slots' => 'Paikat',
        ],
        'actions' => [
            'details' => 'Yksityiskohdat',
            'view_faceit' => 'Näytä FACEITissa',
            'view_matches' => 'Näytä ottelut',
            'results' => 'Tulokset',
            'close' => 'Sulje',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Ladataan yksityiskohtia...',
            'subtitle' => 'Haetaan turnauksen tietoja',
        ],
        'error' => [
            'title' => 'Latausvirhe',
            'subtitle' => 'Turnauksen yksityiskohtia ei voitu ladata',
        ],
        'sections' => [
            'description' => 'Kuvaus',
            'information' => 'Tiedot',
            'matches' => 'Turnauksen ottelut',
            'results' => 'Turnauksen tulokset',
            'default_description' => 'Tämä turnaus on osa virallista CS2-kilpailua, jonka järjestää FACEIT.',
        ],
        'matches' => [
            'loading' => 'Ladataan otteluita...',
            'no_matches' => 'Tälle turnaukselle ei ole saatavilla otteluita',
            'error' => 'Virhe otteluiden latauksessa',
            'status' => [
                'finished' => 'Päättynyt',
                'ongoing' => 'Käynnissä',
                'upcoming' => 'Tulossa',
            ]
        ],
        'results' => [
            'loading' => 'Ladataan tuloksia...',
            'no_results' => 'Tälle turnaukselle ei ole saatavilla tuloksia',
            'error' => 'Virhe tulosten latauksessa',
            'position' => 'Sijoitus',
        ]
    ],
    'pagination' => [
        'previous' => 'Edellinen',
        'next' => 'Seuraava',
        'page' => 'Sivu',
    ],
    'empty_state' => [
        'title' => 'Turnauksia ei löytynyt',
        'subtitle' => 'Yritä muuttaa suodattimiasi tai hae jotain muuta',
        'reset_button' => 'Nollaa suodattimet',
    ],
    'errors' => [
        'search' => 'Hakuvirhe',
        'loading' => 'Virhe turnausten latauksessa',
        'api' => 'API-virhe',
        'network' => 'Yhteysvirhe',
    ]
];
