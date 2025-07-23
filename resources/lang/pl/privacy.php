<?php
return [
    'title' => 'Polityka prywatności - Faceit Scope',
    'header' => [
        'title' => 'Polityka prywatności',
        'subtitle' => 'Rozszerzenie Faceit Scope',
        'last_updated' => 'Ostatnio zaktualizowano: 23 lipca 2025',
    ],
    'introduction' => [
        'title' => '1. Wprowadzenie',
        'content' => 'Faceit Scope to rozszerzenie do przeglądarki, które analizuje mecze CS2 na FACEIT w celu wyświetlania statystyk i przewidywań. Szanujemy Twoją prywatność i zobowiązujemy się do ochrony Twoich danych osobowych.',
    ],
    'data_collected' => [
        'title' => '2. Zbierane dane',
        'temporary_data' => [
            'title' => '2.1 Dane przetwarzane tymczasowo (nie przechowywane)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Publiczne nazwy użytkowników FACEIT:',
                    'description' => 'Pseudonimy gamingowe już publicznie wyświetlane na FACEIT, tymczasowo odczytywane do analizy',
                ],
                'public_stats' => [
                    'title' => 'Publiczne statystyki gry:',
                    'description' => 'K/D, wskaźnik wygranych, zagrane mapy (przez publiczne API FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'ID meczów:',
                    'description' => 'Wyodrębnione z URL w celu identyfikacji meczu do analizy',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Dane przechowywane lokalnie (tylko tymczasowa pamięć podręczna)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Wyniki analizy:',
                    'description' => 'Przechowywane na Twoim urządzeniu maksymalnie przez 5 minut, aby uniknąć duplikowania wywołań API',
                ],
                'user_preferences' => [
                    'title' => 'Preferencje użytkownika:',
                    'description' => 'Ustawienia rozszerzenia (włączenie/wyłączenie powiadomień)',
                ],
            ],
        ],
        'important_note' => 'Ważne: Nie zbieramy ani nie zapisujemy danych umożliwiających identyfikację osoby. Wszystkie przetwarzane dane są już publiczne na FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Wykorzystanie danych',
        'description' => 'Zbierane dane są wykorzystywane wyłącznie do:',
        'items' => [
            'display_stats' => 'Wyświetlania statystyk graczy w interfejsie FACEIT',
            'predictions' => 'Obliczania przewidywań zwycięskich drużyn',
            'map_recommendations' => 'Rekomendowania najlepszych/najgorszych map dla drużyn',
            'performance' => 'Poprawy wydajności poprzez tymczasowe buforowanie',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Udostępnianie danych',
        'no_third_party' => [
            'title' => '4.1 Brak udostępniania stronom trzecim',
            'items' => [
                'no_selling' => 'Nie sprzedajemy żadnych danych stronom trzecim',
                'no_transfer' => 'Nie przekazujemy żadnych danych osobowych',
                'local_analysis' => 'Wszystkie analizy są przeprowadzane lokalnie w Twojej przeglądarce',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API FACEIT',
            'items' => [
                'public_api' => 'Rozszerzenie używa wyłącznie oficjalnego publicznego API FACEIT',
                'no_private_data' => 'Nie zbiera danych prywatnych ani wrażliwych',
                'public_stats' => 'Wszystkie używane statystyki są publicznie dostępne',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Bezpieczeństwo i przechowywanie',
        'local_storage' => [
            'title' => '5.1 Tylko lokalne przechowywanie',
            'items' => [
                'local_only' => 'Wszystkie dane są przechowywane lokalnie na Twoim urządzeniu',
                'no_server_transmission' => 'Żadne dane nie są przesyłane na nasze serwery',
                'auto_delete' => 'Pamięć podręczna jest automatycznie usuwana po 5 minutach',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Ograniczony dostęp',
            'items' => [
                'faceit_only' => 'Rozszerzenie uzyskuje dostęp tylko do stron FACEIT, które odwiedzasz',
                'no_other_access' => 'Nie uzyskuje dostępu do innych stron internetowych ani danych osobowych',
                'no_tracking' => 'Nie śledzi Twojego przeglądania',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Twoje prawa',
        'data_control' => [
            'title' => '6.1 Kontrola danych',
            'items' => [
                'clear_cache' => 'Możesz w każdej chwili wyczyścić pamięć podręczną przez popup rozszerzenia',
                'uninstall' => 'Możesz odinstalować rozszerzenie, aby usunąć wszystkie dane',
                'disable_notifications' => 'Możesz wyłączyć powiadomienia w ustawieniach',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Dane publiczne',
            'items' => [
                'already_public' => 'Wszystkie analizowane dane są już publiczne na FACEIT',
                'no_private_info' => 'Rozszerzenie nie ujawnia żadnych informacji prywatnych',
                'no_personal_data' => 'Nie zbiera danych umożliwiających identyfikację osoby',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Pliki cookie i technologie śledzenia',
        'description' => 'Rozszerzenie Faceit Scope:',
        'does_not_use' => [
            'title' => 'Nie używa:',
            'items' => [
                'no_cookies' => 'Plików cookie',
                'no_ad_tracking' => 'Śledzenia reklamowego',
                'no_behavioral_analysis' => 'Analizy behawioralnej',
            ],
        ],
        'uses_only' => [
            'title' => 'Używa tylko:',
            'items' => [
                'local_storage' => 'Lokalnej pamięci przeglądarki',
                'temp_cache' => 'Tymczasowej pamięci podręcznej (maksymalnie 5 minut)',
                'public_api' => 'Publicznego API FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Aktualizacje tej polityki',
        'content' => 'Możemy aktualizować tę politykę prywatności. Zmiany zostaną opublikowane na tej stronie i w razie potrzeby zostaniesz powiadomiony przez aktualizację rozszerzenia.',
    ],
    'contact' => [
        'title' => '9. Kontakt',
        'description' => 'W przypadku pytań dotyczących tej polityki prywatności:',
        'website' => 'Strona internetowa: ',
        'email' => 'Email: ',
    ],
    'compliance' => [
        'title' => '10. Zgodność z przepisami',
        'description' => 'To rozszerzenie jest zgodne z:',
        'items' => [
            'gdpr' => 'Ogólnym rozporządzeniem o ochronie danych (RODO)',
            'chrome_store' => 'Polityką Chrome Web Store',
            'faceit_terms' => 'Warunkami korzystania z API FACEIT',
        ],
    ],
];
