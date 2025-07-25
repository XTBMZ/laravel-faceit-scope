<?php
return [
    'title' => 'Политика конфиденциальности - Faceit Scope',
    'header' => [
        'title' => 'Политика конфиденциальности',
        'subtitle' => 'Расширение Faceit Scope',
        'last_updated' => 'Последнее обновление: 23 июля 2025',
    ],
    'introduction' => [
        'title' => '1. Введение',
        'content' => 'Faceit Scope - это расширение браузера, которое анализирует матчи CS2 FACEIT для отображения статистики и предсказаний. Мы уважаем вашу конфиденциальность и привержены защите ваших персональных данных.',
    ],
    'data_collected' => [
        'title' => '2. Собираемые данные',
        'temporary_data' => [
            'title' => '2.1 Временно обрабатываемые данные (не сохраняются)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Публичные имена пользователей FACEIT:',
                    'description' => 'Игровые псевдонимы, уже публично отображаемые в FACEIT, временно читаются для анализа',
                ],
                'public_stats' => [
                    'title' => 'Публичная игровая статистика:',
                    'description' => 'K/D, процент побед, сыгранные карты (через публичный API FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'ID матчей:',
                    'description' => 'Извлеченные из URL для идентификации матчей для анализа',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Локально сохраняемые данные (только временный кэш)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Результаты анализа:',
                    'description' => 'Сохраняются максимум 5 минут на вашем устройстве, чтобы избежать повторных вызовов API',
                ],
                'user_preferences' => [
                    'title' => 'Пользовательские настройки:',
                    'description' => 'Настройки расширения (уведомления включены/отключены)',
                ],
            ],
        ],
        'important_note' => 'Важно: Никакие персональные идентифицируемые данные не собираются и не сохраняются. Все обрабатываемые данные уже публичны в FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Использование данных',
        'description' => 'Собранные данные используются исключительно для:',
        'items' => [
            'display_stats' => 'Отображения статистики игроков в интерфейсе FACEIT',
            'predictions' => 'Расчета предсказаний команды-победителя',
            'map_recommendations' => 'Рекомендации лучших/худших карт для команды',
            'performance' => 'Улучшения производительности через временное кэширование',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Обмен данными',
        'no_third_party' => [
            'title' => '4.1 Никакого обмена с третьими сторонами',
            'items' => [
                'no_selling' => 'Мы не продаем никакие данные третьим сторонам',
                'no_transfer' => 'Мы не передаем никаких персональных данных',
                'local_analysis' => 'Весь анализ выполняется локально в вашем браузере',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API FACEIT',
            'items' => [
                'public_api' => 'Расширение использует только официальный публичный API FACEIT',
                'no_private_data' => 'Никакие приватные или чувствительные данные не собираются',
                'public_stats' => 'Вся используемая статистика публично доступна',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Безопасность и хранение',
        'local_storage' => [
            'title' => '5.1 Только локальное хранение',
            'items' => [
                'local_only' => 'Все данные сохраняются локально на вашем устройстве',
                'no_server_transmission' => 'Никакие данные не передаются на наши серверы',
                'auto_delete' => 'Кэш автоматически удаляется через 5 минут',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Ограниченный доступ',
            'items' => [
                'faceit_only' => 'Расширение получает доступ только к страницам FACEIT, которые вы посещаете',
                'no_other_access' => 'Нет доступа к другим сайтам или персональным данным',
                'no_tracking' => 'Никакого отслеживания вашего просмотра',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Ваши права',
        'data_control' => [
            'title' => '6.1 Контроль данных',
            'items' => [
                'clear_cache' => 'Вы можете очистить кэш в любое время через всплывающее окно расширения',
                'uninstall' => 'Вы можете удалить расширение для удаления всех данных',
                'disable_notifications' => 'Вы можете отключить уведомления в настройках',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Публичные данные',
            'items' => [
                'already_public' => 'Все анализируемые данные уже публичны в FACEIT',
                'no_private_info' => 'Расширение не раскрывает никакой приватной информации',
                'no_personal_data' => 'Никакие персональные идентифицируемые данные не собираются',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Файлы cookie и технологии отслеживания',
        'description' => 'Расширение Faceit Scope:',
        'does_not_use' => [
            'title' => 'Не использует:',
            'items' => [
                'no_cookies' => 'Никаких файлов cookie',
                'no_ad_tracking' => 'Никакого рекламного отслеживания',
                'no_behavioral_analysis' => 'Никакого поведенческого анализа',
            ],
        ],
        'uses_only' => [
            'title' => 'Использует только:',
            'items' => [
                'local_storage' => 'Локальное хранилище браузера',
                'temp_cache' => 'Временный кэш (максимум 5 минут)',
                'public_api' => 'Публичный API FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Обновления этой политики',
        'content' => 'Мы можем обновлять эту политику конфиденциальности. Изменения будут опубликованы на этой странице с новой датой и уведомлены через обновление расширения при необходимости.',
    ],
    'contact' => [
        'title' => '9. Контакты',
        'description' => 'По любым вопросам об этой политике конфиденциальности:',
        'website' => 'Веб-сайт:',
        'email' => 'Email:',
    ],
    'compliance' => [
        'title' => '10. Соответствие нормативным требованиям',
        'description' => 'Это расширение соответствует:',
        'items' => [
            'gdpr' => 'Общему регламенту по защите данных (GDPR)',
            'chrome_store' => 'Политикам Chrome Web Store',
            'faceit_terms' => 'Условиям использования API FACEIT',
        ],
    ],
];
