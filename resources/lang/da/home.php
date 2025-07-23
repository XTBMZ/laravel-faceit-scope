<?php
return [
    'title' => 'Faceit Scope - Analyser dine FACEIT statistikker',
    'hero' => [
        'subtitle' => 'Analyser din præstation på FACEIT ved hjælp af avancerede algoritmer og kunstig intelligens. Opdag dine styrker og forbedr dine færdigheder.',
        'features' => [
            'detailed_stats' => 'Detaljerede statistikker',
            'artificial_intelligence' => 'Kunstig intelligens',
            'predictive_analysis' => 'Forudsigelse-analyse',
        ]
    ],
    'search' => [
        'title' => 'Start analyse',
        'subtitle' => 'Søg efter en spiller eller analyser en kamp for at opdage detaljerede indsigter',
        'player' => [
            'title' => 'Søg spiller',
            'description' => 'Analyser spiller-præstation',
            'placeholder' => 'FACEIT spillernavn...',
            'button' => 'Søg',
            'loading' => 'Søger...',
        ],
        'match' => [
            'title' => 'Analyser kamp',
            'description' => 'AI forudsigelser og dybdegående analyse',
            'placeholder' => 'Kamp ID eller URL...',
            'button' => 'Analyser',
            'loading' => 'Analyserer...',
        ],
        'errors' => [
            'empty_player' => 'Indtast venligst et spillernavn',
            'empty_match' => 'Indtast venligst et kamp ID eller URL',
            'player_not_found' => 'Spilleren ":player" blev ikke fundet på FACEIT',
            'no_cs_stats' => 'Spilleren ":player" har aldrig spillet CS2/CS:GO på FACEIT',
            'no_stats_available' => 'Ingen statistikker tilgængelige for ":player"',
            'match_not_found' => 'Ingen kamp fundet for dette ID eller URL',
            'invalid_format' => 'Ugyldigt kamp ID eller URL format. Gyldige eksempler:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'For mange anmodninger. Vent venligst.',
            'access_forbidden' => 'Adgang forbudt. API nøgle problem.',
            'generic_player' => 'Fejl ved søgning efter ":player". Kontroller forbindelsen.',
            'generic_match' => 'Fejl ved hentning af kamp. Kontroller ID eller URL.',
        ]
    ],
    'features' => [
        'title' => 'Funktioner',
        'subtitle' => 'Kraftfulde værktøjer til at analysere og forbedre din præstation',
        'advanced_stats' => [
            'title' => 'Avancerede statistikker',
            'description' => 'Analyser din præstation efter kort, spor din K/D, headshots og opdag dine bedste/værste kort med vores algoritmer.',
        ],
        'ai' => [
            'title' => 'Kunstig intelligens',
            'description' => 'Kamp forudsigelser, nøglespiller identifikation, rolle-analyse og personlige anbefalinger baseret på dine data.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobby analyse',
            'description' => 'Opdag kamp-sammensætning, fordele og få detaljerede forudsigelser for kamp-resultater.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Sådan fungerer det',
        'subtitle' => 'Videnskabelig tilgang til FACEIT præstations-analyse',
        'steps' => [
            'data_collection' => [
                'title' => 'Data indsamling',
                'description' => 'Vi bruger kun den officielle FACEIT API til at få alle dine statistikker på en gennemsigtig og lovlig måde.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algoritmisk analyse',
                'description' => 'Vores algoritmer analyserer dine data gennem normalisering, vægtning og konfidensberegninger for præcise indsigter.',
            ],
            'personalized_insights' => [
                'title' => 'Personlige indsigter',
                'description' => 'Få detaljerede analyser, forudsigelser og anbefalinger til at forbedre din spil-præstation.',
            ]
        ]
    ]
];
