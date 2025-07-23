<?php
return [
    'title' => 'O nas - Faceit Scope',
    'hero' => [
        'title' => 'O nas',
        'subtitle' => 'Faceit Scope wykorzystuje zaawansowane algorytmy i sztuczną inteligencję do analizy Twojej wydajności na FACEIT. To projekt rozwijany z pasją.',
    ],
    'project' => [
        'title' => 'Projekt',
        'description_1' => 'Umożliwia dogłębną analizę wydajności.',
        'description_2' => 'Całkowicie opracowany przez',
        'description_3' => ', ten projekt używa wyłącznie oficjalnego API FACEIT do uzyskiwania wszystkich danych w sposób przejrzysty i zgodny z prawem.',
        'description_4' => 'Wszystko pochodzi bezpośrednio z serwerów FACEIT i jest analizowane przez nasze zastrzeżone algorytmy.',
        'stats' => [
            'developer' => 'Deweloper',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Jak to działa',
        'subtitle' => 'Zaawansowane algorytmy analizują Twoje dane FACEIT, aby zapewnić Ci precyzyjne spostrzeżenia',
        'pis' => [
            'title' => 'Wynik Wpływu Gracza (PIS)',
            'combat' => [
                'title' => 'Walka (35%)',
                'description' => 'K/D, ADR i procent headshot, znormalizowane logarytmicznie',
            ],
            'game_sense' => [
                'title' => 'Świadomość gry (25%)',
                'description' => 'Umiejętności entry, clutch i sniper, zaawansowane kombinacje',
            ],
            'utility' => [
                'title' => 'Użyteczność (15%)',
                'description' => 'Wsparcie i użycie narzędzi, ważona efektywność',
            ],
            'consistency' => [
                'title' => 'Spójność + Doświadczenie (25%)',
                'description' => 'Wskaźnik wygranych, streak i niezawodność danych',
            ],
            'level_coefficient' => [
                'title' => 'Krytyczny współczynnik poziomu:',
                'description' => 'Gracz poziomu 10 z K/D 1,0 jest oceniany wyżej niż gracz poziomu 2 z K/D 1,5, ponieważ gra przeciwko silniejszym przeciwnikom.',
            ],
        ],
        'roles' => [
            'title' => 'Inteligentne przypisanie ról',
            'calculations_title' => 'Obliczenia ocen ról',
            'priority_title' => 'Priorytet przypisania',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Specyficzne kryteria: Wskaźnik entry > 25% I Sukces entry > 55%',
            ],
            'support' => [
                'title' => 'Wsparcie',
                'criteria' => 'Specyficzne kryteria: Flash > 0.4/rundę I Sukces flash > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Specyficzne kryteria: Wskaźnik sniper > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (jeśli sniper > 15%)',
                'entry' => 'Entry Fragger (jeśli entry > 25% + sukces > 55%)',
                'support' => 'Wsparcie (jeśli flash > 0.4 + sukces > 50%)',
                'clutcher' => 'Clutcher (jeśli 1v1 > 40%)',
                'fragger' => 'Fragger (jeśli K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (domyślnie, jeśli brak innych kryteriów)',
            ],
        ],
        'maps' => [
            'title' => 'Algorytm analizy map',
            'normalization' => [
                'title' => 'Normalizacja logarytmiczna',
            ],
            'weighting' => [
                'title' => 'Zaawansowane ważenie',
                'win_rate' => 'Wskaźnik wygranych:',
                'consistency' => 'Spójność:',
            ],
            'reliability' => [
                'title' => 'Współczynnik niezawodności',
            ],
        ],
        'predictions' => [
            'title' => 'Przewidywania meczów',
            'team_strength' => [
                'title' => 'Siła drużyny',
                'average_score' => [
                    'title' => 'Średnia ważona',
                    'description' => 'Średnia z 5 wyników PIS + bonus za równowagę ról',
                ],
                'role_balance' => [
                    'title' => 'Równowaga ról',
                    'description' => 'Drużyna z Entry Fragger + Wsparcie + AWPer + Clutcher + Fragger otrzyma znaczący bonus w porównaniu do drużyny 5 fraggerów.',
                ],
            ],
            'probability' => [
                'title' => 'Obliczenie prawdopodobieństwa',
                'match_winner' => [
                    'title' => 'Zwycięzca meczu',
                    'description' => 'Im większa różnica w sile, tym dokładniejsze przewidywanie',
                ],
                'predicted_mvp' => [
                    'title' => 'Przewidywany MVP',
                    'description' => 'Gracz z',
                    'description_end' => 'zostanie przewidywanym MVP spośród 10 uczestników',
                    'highest_score' => 'najwyższym wynikiem PIS',
                ],
                'confidence' => [
                    'title' => 'Poziom pewności',
                    'description' => 'Na podstawie różnicy sił: Bardzo wysoki (>3), Wysoki (>2), Średni (>1), Niski (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Kontakt',
        'subtitle' => 'To projekt rozwijany z pasją. Zapraszam do kontaktu w celu przekazania opinii lub sugestii.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope nie jest powiązany z FACEIT Ltd. Ten projekt używa publicznego API FACEIT zgodnie z jego warunkami usługi. Algorytmy przewidywania oparte są na analizie statystycznej i nie gwarantują wyników meczów.',
    ],
];
