<?php
return [
    'title' => 'Turnieje CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Turnieje CS2',
        'subtitle' => 'Odkryj oficjalne turnieje CS2 FACEIT, śledź najlepsze wydarzenia esportowe w czasie rzeczywistym',
        'features' => [
            'ongoing' => 'Trwające turnieje',
            'upcoming' => 'Nadchodzące wydarzenia',
            'premium' => 'Turnieje premium',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Trwające',
            'upcoming' => 'Nadchodzące',
            'past' => 'Zakończone',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Szukaj turnieje...',
            'button' => 'Szukaj',
        ],
        'stats' => [
            'ongoing' => 'Trwające',
            'upcoming' => 'Nadchodzące',
            'prize_pools' => 'Pule nagród',
            'participants' => 'Uczestnicy',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Trwający',
            'upcoming' => 'Nadchodzący',
            'finished' => 'Zakończony',
            'cancelled' => 'Anulowany',
        ],
        'info' => [
            'participants' => 'Uczestnicy',
            'prize_pool' => 'Pula nagród',
            'registrations' => 'Rejestracje',
            'organizer' => 'Organizator',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Poziom',
            'slots' => 'Miejsca',
        ],
        'actions' => [
            'details' => 'Szczegóły',
            'view_faceit' => 'Zobacz na FACEIT',
            'view_matches' => 'Zobacz mecze',
            'results' => 'Wyniki',
            'close' => 'Zamknij',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Ładowanie szczegółów...',
            'subtitle' => 'Pobieranie informacji o turnieju',
        ],
        'error' => [
            'title' => 'Błąd ładowania',
            'subtitle' => 'Nie można załadować szczegółów turnieju',
        ],
        'sections' => [
            'description' => 'Opis',
            'information' => 'Informacje',
            'matches' => 'Mecze turnieju',
            'results' => 'Wyniki turnieju',
            'default_description' => 'Ten turniej jest częścią oficjalnych zawodów CS2 organizowanych przez FACEIT.',
        ],
        'matches' => [
            'loading' => 'Ładowanie meczów...',
            'no_matches' => 'Brak dostępnych meczów dla tego turnieju',
            'error' => 'Błąd ładowania meczów',
            'status' => [
                'finished' => 'Zakończony',
                'ongoing' => 'Trwający',
                'upcoming' => 'Nadchodzący',
            ]
        ],
        'results' => [
            'loading' => 'Ładowanie wyników...',
            'no_results' => 'Brak dostępnych wyników dla tego turnieju',
            'error' => 'Błąd ładowania wyników',
            'position' => 'Pozycja',
        ]
    ],
    'pagination' => [
        'previous' => 'Poprzednia',
        'next' => 'Następna',
        'page' => 'Strona',
    ],
    'empty_state' => [
        'title' => 'Nie znaleziono turniejów',
        'subtitle' => 'Spróbuj zmodyfikować filtry lub wyszukać coś innego',
        'reset_button' => 'Resetuj filtry',
    ],
    'errors' => [
        'search' => 'Błąd wyszukiwania',
        'loading' => 'Błąd ładowania turniejów',
        'api' => 'Błąd API',
        'network' => 'Błąd połączenia',
    ]
];
