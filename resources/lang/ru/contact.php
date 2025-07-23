<?php
return [
    'title' => 'Контакты - Faceit Scope',
    'hero' => [
        'title' => 'Контакты',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Разработчик',
            'name_label' => 'Имя',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Ответ',
            'average_delay' => 'Средняя задержка',
            'delay_value' => '24ч',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Тип сообщения',
            'required' => '*',
            'placeholder' => 'Выберите тип',
            'options' => [
                'bug' => 'Сообщить о баге',
                'suggestion' => 'Предложение',
                'question' => 'Вопрос',
                'feedback' => 'Отзыв',
                'other' => 'Другое',
            ],
        ],
        'subject' => [
            'label' => 'Тема',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Ник Faceit',
            'optional' => '(необязательно)',
        ],
        'message' => [
            'label' => 'Сообщение',
            'required' => '*',
            'character_count' => 'символов',
        ],
        'submit' => [
            'send' => 'Отправить',
            'sending' => 'Отправка...',
        ],
        'privacy_note' => 'Ваши данные используются только для обработки вашего запроса',
    ],
    'messages' => [
        'success' => [
            'title' => 'Сообщение успешно отправлено',
            'ticket_id' => 'ID тикета:',
        ],
        'error' => [
            'title' => 'Ошибка отправки',
            'connection' => 'Ошибка соединения. Попробуйте снова.',
            'generic' => 'Произошла ошибка.',
        ],
    ],
];
