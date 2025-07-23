<?php
return [
    'title' => 'Контакт - Faceit Scope',
    'hero' => [
        'title' => 'Зв\'яжіться з нами',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Розробник',
            'name_label' => 'Ім\'я',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Відповідь',
            'average_delay' => 'Середня затримка',
            'delay_value' => '24 години',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Тип повідомлення',
            'required' => '*',
            'placeholder' => 'Виберіть тип',
            'options' => [
                'bug' => 'Повідомити про помилку',
                'suggestion' => 'Пропозиція',
                'question' => 'Питання',
                'feedback' => 'Відгук',
                'other' => 'Інше',
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
            'label' => 'Нікнейм Faceit',
            'optional' => '(необов\'язково)',
        ],
        'message' => [
            'label' => 'Повідомлення',
            'required' => '*',
            'character_count' => 'символів',
        ],
        'submit' => [
            'send' => 'Надіслати',
            'sending' => 'Надсилання...',
        ],
        'privacy_note' => 'Ваші дані використовуються виключно для обробки вашого запиту',
    ],
    'messages' => [
        'success' => [
            'title' => 'Повідомлення надіслано успішно',
            'ticket_id' => 'ID заявки: ',
        ],
        'error' => [
            'title' => 'Помилка надсилання',
            'connection' => 'Помилка з\'єднання. Спробуйте знову.',
            'generic' => 'Сталася помилка. ',
        ],
    ],
];
