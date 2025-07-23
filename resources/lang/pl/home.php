<?php
return [
    'title' => 'Faceit Scope - Analizuj swoje statystyki FACEIT',
    'hero' => [
        'subtitle' => 'Analizuj swoją wydajność na FACEIT za pomocą zaawansowanych algorytmów i sztucznej inteligencji. Odkryj swoje mocne strony i popraw umiejętności.',
        'features' => [
            'detailed_stats' => 'Szczegółowe statystyki',
            'artificial_intelligence' => 'Sztuczna inteligencja',
            'predictive_analysis' => 'Analiza predykcyjna',
        ]
    ],
    'search' => [
        'title' => 'Rozpocznij analizę',
        'subtitle' => 'Wyszukaj gracza lub przeanalizuj mecz, aby odkryć szczegółowe spostrzeżenia',
        'player' => [
            'title' => 'Wyszukaj gracza',
            'description' => 'Analizuj wydajność gracza',
            'placeholder' => 'Nazwa gracza FACEIT...',
            'button' => 'Szukaj',
            'loading' => 'Wyszukiwanie...',
        ],
        'match' => [
            'title' => 'Analizuj mecz',
            'description' => 'Przewidywania AI i dogłębna analiza',
            'placeholder' => 'ID meczu lub URL...',
            'button' => 'Analizuj',
            'loading' => 'Analizowanie...',
        ],
        'errors' => [
            'empty_player' => 'Wprowadź nazwę gracza',
            'empty_match' => 'Wprowadź ID meczu lub URL',
            'player_not_found' => 'Gracz ":player" nie znaleziony na FACEIT',
            'no_cs_stats' => 'Gracz ":player" nigdy nie grał w CS2/CS:GO na FACEIT',
            'no_stats_available' => 'Brak dostępnych statystyk dla ":player"',
            'match_not_found' => 'Nie znaleziono meczu dla tego ID lub URL',
            'invalid_format' => 'Nieprawidłowy format ID meczu lub URL. Prawidłowe przykłady:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Zbyt wiele żądań. Proszę czekać.',
            'access_forbidden' => 'Dostęp zabroniony. Problem z kluczem API.',
            'generic_player' => 'Błąd wyszukiwania ":player". Sprawdź połączenie.',
            'generic_match' => 'Błąd pobierania meczu. Sprawdź ID lub URL.',
        ]
    ],
    'features' => [
        'title' => 'Funkcje',
        'subtitle' => 'Potężne narzędzia do analizy i poprawy wydajności',
        'advanced_stats' => [
            'title' => 'Zaawansowane statystyki',
            'description' => 'Analizuj swoją wydajność według map, śledź K/D, headshot i odkryj swoje najlepsze/najgorsze mapy dzięki naszym algorytmom.',
        ],
        'ai' => [
            'title' => 'Sztuczna inteligencja',
            'description' => 'Przewidywania meczów, identyfikacja kluczowych graczy, analiza ról i spersonalizowane rekomendacje oparte na Twoich danych.',
        ],
        'lobby_analysis' => [
            'title' => 'Analiza lobby',
            'description' => 'Odkryj skład meczu, mocne strony i uzyskaj szczegółowe przewidywania wyników meczu.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Jak to działa',
        'subtitle' => 'Naukowe podejście do analizy wydajności FACEIT',
        'steps' => [
            'data_collection' => [
                'title' => 'Zbieranie danych',
                'description' => 'Używamy wyłącznie oficjalnego API FACEIT do uzyskiwania wszystkich Twoich statystyk w sposób przejrzysty i zgodny z prawem.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Analiza algorytmiczna',
                'description' => 'Nasze algorytmy analizują Twoje dane poprzez normalizację, ważenie i obliczenia pewności dla precyzyjnych spostrzeżeń.',
            ],
            'personalized_insights' => [
                'title' => 'Spersonalizowane spostrzeżenia',
                'description' => 'Uzyskaj szczegółowe analizy, przewidywania i rekomendacje, aby poprawić swoją wydajność w grze.',
            ]
        ]
    ]
];
