<?php
return [
    'title' => 'Порівняння гравців - Faceit Scope',
    'hero' => [
        'title' => 'Порівняння гравців',
        'subtitle' => 'Порівняйте продуктивність двох гравців CS2',
    ],
    'search' => [
        'player1' => 'Гравець 1',
        'player2' => 'Гравець 2',
        'placeholder' => 'Нікнейм Faceit...',
        'button' => 'Почати порівняння',
        'loading' => 'Аналізую',
        'loading_text' => 'Порівнюю гравців',
        'errors' => [
            'both_players' => 'Введіть два нікнейми',
            'different_players' => 'Введіть два різних нікнейми',
        ]
    ],
    'loading' => [
        'title' => 'Аналізую',
        'messages' => [
            'player1_data' => 'Отримання даних гравця 1',
            'player2_data' => 'Отримання даних гравця 2',
            'analyzing_stats' => 'Аналіз статистики',
            'calculating_scores' => 'Розрахунок результатів продуктивності',
            'comparing_roles' => 'Порівняння ігрових ролей',
            'generating_report' => 'Генерація підсумкового звіту'
        ]
    ],
    'tabs' => [
        'overview' => 'Огляд',
        'detailed' => 'Детальна статистика',
        'maps' => 'Карти'
    ],
    'winner' => [
        'analysis_complete' => 'Аналіз завершено',
        'wins_analysis' => ':winner виграє аналіз ШІ',
        'confidence' => 'Впевненість: :percentage%',
        'performance_score' => 'Результат продуктивності',
        'matches' => 'Матчі'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Результати продуктивності',
            'elo_impact' => 'Вплив ELO',
            'combat_performance' => 'Бойова продуктивність',
            'experience' => 'Досвід',
            'advanced_stats' => 'Розширена статистика'
        ],
        'key_stats' => [
            'title' => 'Ключова статистика',
            'kd_ratio' => 'Співвідношення K/D',
            'win_rate' => 'Показник перемог',
            'headshots' => 'Хедшоти',
            'adr' => 'ADR',
            'entry_success' => 'Успіх entry',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Як розраховуються результати?',
            'elo_impact' => [
                'title' => 'Вплив ELO (35%)',
                'description' => 'Рівень ELO - найважливіший фактор, оскільки він безпосередньо відображає рівень гри проти суперників рівної сили.'
            ],
            'combat_performance' => [
                'title' => 'Бойова продуктивність (25%)',
                'description' => 'Поєднує K/D, показник перемог, ADR та рівень Faceit для оцінки бойової ефективності.'
            ],
            'experience' => [
                'title' => 'Досвід (20%)',
                'description' => 'Кількість зіграних матчів, множник, що базується на накопиченому досвіді.'
            ],
            'advanced_stats' => [
                'title' => 'Розширена статистика (20%)',
                'description' => 'Хедшоти, навички entry та clutch для глибокого аналізу стилю гри.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Загальна продуктивність',
                'stats' => [
                    'total_matches' => 'Загальні матчі',
                    'win_rate' => 'Показник перемог',
                    'wins' => 'Перемоги',
                    'avg_kd' => 'Середнє співвідношення K/D',
                    'adr' => 'ADR (Урон за раунд)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Бій та точність',
                'stats' => [
                    'avg_headshots' => 'Середні хедшоти',
                    'total_headshots' => 'Загальні хедшоти',
                    'total_kills' => 'Вбивства (розширена статистика)',
                    'total_damage' => 'Загальний урон'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Показник entry',
                    'entry_success' => 'Успіх entry',
                    'total_entries' => 'Загальні спроби',
                    'successful_entries' => 'Успішні entry'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Ситуації clutch',
                'stats' => [
                    '1v1_win_rate' => 'Показник перемог 1v1',
                    '1v2_win_rate' => 'Показник перемог 1v2',
                    '1v1_situations' => 'Ситуації 1v1',
                    '1v1_wins' => 'Перемоги 1v1',
                    '1v2_situations' => 'Ситуації 1v2',
                    '1v2_wins' => 'Перемоги 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility та підтримка',
                'stats' => [
                    'flash_success' => 'Успіх flash',
                    'flashes_per_round' => 'Flash за раунд',
                    'total_flashes' => 'Загальні flash',
                    'successful_flashes' => 'Успішні flash',
                    'enemies_flashed_per_round' => 'Осліплені вороги за раунд',
                    'total_enemies_flashed' => 'Загальні осліплені вороги',
                    'utility_success' => 'Успіх utility',
                    'utility_damage_per_round' => 'Урон utility за раунд',
                    'total_utility_damage' => 'Загальний урон utility'
                ]
            ],
            'sniper_special' => [
                'title' => 'Снайпер та спеціальна зброя',
                'stats' => [
                    'sniper_kill_rate' => 'Показник вбивств снайпера',
                    'sniper_kills_per_round' => 'Вбивства снайпера за раунд',
                    'total_sniper_kills' => 'Загальні вбивства снайпера'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Серії та послідовність',
                'stats' => [
                    'current_streak' => 'Поточна серія',
                    'longest_streak' => 'Найдовша серія'
                ]
            ]
        ],
        'legend' => 'Значення зеленого кольору вказують на гравця, який краще справляється з цією статистикою'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Немає спільних карт',
            'description' => 'Два гравці не мають спільних карт з достатніми даними.'
        ],
        'dominates' => ':player домінує',
        'win_rate' => 'Показник перемог (:matches матчів)',
        'kd_ratio' => 'Співвідношення K/D',
        'headshots' => 'Хедшоти',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Підсумок карт',
            'maps_dominated' => 'Домінуючі карти',
            'best_map' => 'Найкраща карта',
            'none' => 'Немає'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Спеціалізується на атаці позицій'
        ],
        'support' => [
            'name' => 'Підтримка',
            'description' => 'Майстер підтримки команди'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Експерт у складних ситуаціях'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Спеціаліст з елімінації'
        ],
        'versatile' => [
            'name' => 'Універсальний',
            'description' => 'Збалансований гравець'
        ]
    ],
    'error' => [
        'title' => 'Помилка',
        'default_message' => 'Сталася помилка під час порівняння',
        'retry' => 'Повторити',
        'player_not_found' => 'Гравець ":player" не знайдений',
        'stats_error' => 'Помилка отримання статистики: :status'
    ]
];
