<?php
return [
    'title' => 'Сравнение игроков - Faceit Scope',
    'hero' => [
        'title' => 'Сравнение игроков',
        'subtitle' => 'Сравните производительность двух игроков CS2',
    ],
    'search' => [
        'player1' => 'Игрок 1',
        'player2' => 'Игрок 2',
        'placeholder' => 'Ник Faceit...',
        'button' => 'Начать сравнение',
        'loading' => 'Анализ в процессе',
        'loading_text' => 'Сравнение игроков',
        'errors' => [
            'both_players' => 'Пожалуйста, введите оба ника',
            'different_players' => 'Пожалуйста, введите два разных ника',
        ]
    ],
    'loading' => [
        'title' => 'Анализ в процессе',
        'messages' => [
            'player1_data' => 'Получение данных игрока 1',
            'player2_data' => 'Получение данных игрока 2',
            'analyzing_stats' => 'Анализ статистики',
            'calculating_scores' => 'Расчет оценок производительности',
            'comparing_roles' => 'Сравнение игровых ролей',
            'generating_report' => 'Создание финального отчета'
        ]
    ],
    'tabs' => [
        'overview' => 'Обзор',
        'detailed' => 'Детальная статистика',
        'maps' => 'Карты'
    ],
    'winner' => [
        'analysis_complete' => 'Полный анализ завершен',
        'wins_analysis' => ':winner выигрывает ИИ-анализ',
        'confidence' => 'Уверенность: :percentage%',
        'performance_score' => 'Оценка производительности',
        'matches' => 'Матчи'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Оценки производительности',
            'elo_impact' => 'Влияние ELO',
            'combat_performance' => 'Боевая эффективность',
            'experience' => 'Опыт',
            'advanced_stats' => 'Продвинутая статистика'
        ],
        'key_stats' => [
            'title' => 'Ключевая статистика',
            'kd_ratio' => 'Соотношение K/D',
            'win_rate' => 'Процент побед',
            'headshots' => 'Хедшоты',
            'adr' => 'ADR',
            'entry_success' => 'Успех входа',
            'clutch_1v1' => 'Клатч 1v1'
        ],
        'calculation_info' => [
            'title' => 'Как рассчитываются оценки?',
            'elo_impact' => [
                'title' => 'Влияние ELO (35%)',
                'description' => 'Уровень ELO - самый важный фактор, так как он напрямую отражает уровень игры против равных по силе противников.'
            ],
            'combat_performance' => [
                'title' => 'Боевая эффективность (25%)',
                'description' => 'Объединяет K/D, процент побед, ADR и уровень Faceit для оценки боевой эффективности.'
            ],
            'experience' => [
                'title' => 'Опыт (20%)',
                'description' => 'Количество сыгранных матчей с множителем на основе накопленного опыта.'
            ],
            'advanced_stats' => [
                'title' => 'Продвинутая статистика (20%)',
                'description' => 'Хедшоты, энтри-фрагинг и способности клатча для углубленного анализа стиля игры.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Общая производительность',
                'stats' => [
                    'total_matches' => 'Всего матчей',
                    'win_rate' => 'Процент побед',
                    'wins' => 'Победы',
                    'avg_kd' => 'Среднее соотношение K/D',
                    'adr' => 'ADR (урон/раунд)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Бой и точность',
                'stats' => [
                    'avg_headshots' => 'Средние хедшоты',
                    'total_headshots' => 'Всего хедшотов',
                    'total_kills' => 'Киллы (расширенная статистика)',
                    'total_damage' => 'Общий урон'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Энтри-фрагинг',
                'stats' => [
                    'entry_rate' => 'Процент входа',
                    'entry_success' => 'Процент успеха входа',
                    'total_entries' => 'Всего попыток',
                    'successful_entries' => 'Успешных входов'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Клатч-ситуации',
                'stats' => [
                    '1v1_win_rate' => 'Процент побед 1v1',
                    '1v2_win_rate' => 'Процент побед 1v2',
                    '1v1_situations' => 'Ситуации 1v1',
                    '1v1_wins' => 'Победы 1v1',
                    '1v2_situations' => 'Ситуации 1v2',
                    '1v2_wins' => 'Победы 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Утилиты и поддержка',
                'stats' => [
                    'flash_success' => 'Процент успеха флешек',
                    'flashes_per_round' => 'Флешек за раунд',
                    'total_flashes' => 'Всего флешек',
                    'successful_flashes' => 'Успешных флешек',
                    'enemies_flashed_per_round' => 'Противников ослеплено/раунд',
                    'total_enemies_flashed' => 'Всего противников ослеплено',
                    'utility_success' => 'Процент успеха утилит',
                    'utility_damage_per_round' => 'Урон утилитами/раунд',
                    'total_utility_damage' => 'Общий урон утилитами'
                ]
            ],
            'sniper_special' => [
                'title' => 'Снайпер и спецоружие',
                'stats' => [
                    'sniper_kill_rate' => 'Процент киллов снайпером',
                    'sniper_kills_per_round' => 'Киллов снайпером/раунд',
                    'total_sniper_kills' => 'Всего киллов снайпером'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Серии и стабильность',
                'stats' => [
                    'current_streak' => 'Текущая серия',
                    'longest_streak' => 'Самая длинная серия'
                ]
            ]
        ],
        'legend' => 'Значения зеленым цветом указывают игрока с лучшей производительностью для каждой статистики'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Нет общих карт',
            'description' => 'У обоих игроков нет общих карт с достаточными данными.'
        ],
        'dominates' => ':player доминирует',
        'win_rate' => 'Процент побед (:matches матчей)',
        'kd_ratio' => 'Соотношение K/D',
        'headshots' => 'Хедшоты',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Сводка по картам',
            'maps_dominated' => 'Карт доминирует',
            'best_map' => 'Лучшая карта',
            'none' => 'Нет'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Энтри-фраггер',
            'description' => 'Специализируется на входах на сайты'
        ],
        'support' => [
            'name' => 'Поддержка',
            'description' => 'Мастер командных утилит'
        ],
        'clutcher' => [
            'name' => 'Клатчер',
            'description' => 'Эксперт в сложных ситуациях'
        ],
        'fragger' => [
            'name' => 'Фраггер',
            'description' => 'Специалист по устранению'
        ],
        'versatile' => [
            'name' => 'Универсал',
            'description' => 'Сбалансированный игрок'
        ]
    ],
    'error' => [
        'title' => 'Ошибка',
        'default_message' => 'Произошла ошибка во время сравнения',
        'retry' => 'Попробовать снова',
        'player_not_found' => 'Игрок ":player" не найден',
        'stats_error' => 'Ошибка получения статистики: :status'
    ]
];
