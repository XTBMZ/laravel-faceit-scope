<?php
return [
    'title' => 'Турніри CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Турніри CS2',
        'subtitle' => 'Відкрийте офіційні турніри CS2 FACEIT, стежте за найкращими подіями кіберспорту в реальному часі',
        'features' => [
            'ongoing' => 'Поточні турніри',
            'upcoming' => 'Майбутні події',
            'premium' => 'Преміум турніри',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Поточні',
            'upcoming' => 'Майбутні',
            'past' => 'Завершені',
            'featured' => 'Преміум',
        ],
        'search' => [
            'placeholder' => 'Шукати турніри...',
            'button' => 'Шукати',
        ],
        'stats' => [
            'ongoing' => 'Поточні',
            'upcoming' => 'Майбутні',
            'prize_pools' => 'Призові фонди',
            'participants' => 'Учасники',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Преміум',
            'ongoing' => 'Поточний',
            'upcoming' => 'Майбутній',
            'finished' => 'Завершений',
            'cancelled' => 'Скасований',
        ],
        'info' => [
            'participants' => 'Учасники',
            'prize_pool' => 'Призовий фонд',
            'registrations' => 'Реєстрації',
            'organizer' => 'Організатор',
            'status' => 'Статус',
            'region' => 'Регіон',
            'level' => 'Рівень',
            'slots' => 'Місця',
        ],
        'actions' => [
            'details' => 'Деталі',
            'view_faceit' => 'Переглянути на FACEIT',
            'view_matches' => 'Переглянути матчі',
            'results' => 'Результати',
            'close' => 'Закрити',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Завантаження деталей...',
            'subtitle' => 'Отримання інформації про турнір',
        ],
        'error' => [
            'title' => 'Помилка завантаження',
            'subtitle' => 'Не вдалося завантажити деталі турніру',
        ],
        'sections' => [
            'description' => 'Опис',
            'information' => 'Інформація',
            'matches' => 'Матчі турніру',
            'results' => 'Результати турніру',
            'default_description' => 'Цей турнір є частиною офіційних змагань CS2, організованих FACEIT.',
        ],
        'matches' => [
            'loading' => 'Завантаження матчів...',
            'no_matches' => 'Немає доступних матчів для цього турніру',
            'error' => 'Помилка завантаження матчів',
            'status' => [
                'finished' => 'Завершений',
                'ongoing' => 'Поточний',
                'upcoming' => 'Майбутній',
            ]
        ],
        'results' => [
            'loading' => 'Завантаження результатів...',
            'no_results' => 'Немає доступних результатів для цього турніру',
            'error' => 'Помилка завантаження результатів',
            'position' => 'Позиція',
        ]
    ],
    'pagination' => [
        'previous' => 'Попередня',
        'next' => 'Наступна',
        'page' => 'Сторінка',
    ],
    'empty_state' => [
        'title' => 'Турніри не знайдено',
        'subtitle' => 'Спробуйте змінити фільтри або пошукати щось інше',
        'reset_button' => 'Скинути фільтри',
    ],
    'errors' => [
        'search' => 'Помилка пошуку',
        'loading' => 'Помилка завантаження турнірів',
        'api' => 'Помилка API',
        'network' => 'Помилка з\'єднання',
    ]
];
