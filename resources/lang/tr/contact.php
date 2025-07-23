<?php
return [
    'title' => 'İletişim - Faceit Scope',
    'hero' => [
        'title' => 'İletişim',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Geliştirici',
            'name_label' => 'İsim',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Yanıt',
            'average_delay' => 'Ortalama gecikme',
            'delay_value' => '24 saat',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Mesaj türü',
            'required' => '*',
            'placeholder' => 'Türü seçin',
            'options' => [
                'bug' => 'Hata bildir',
                'suggestion' => 'Öneri',
                'question' => 'Soru',
                'feedback' => 'Geri bildirim',
                'other' => 'Diğer',
            ],
        ],
        'subject' => [
            'label' => 'Konu',
            'required' => '*',
        ],
        'email' => [
            'label' => 'E-posta',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit kullanıcı adı',
            'optional' => '(isteğe bağlı)',
        ],
        'message' => [
            'label' => 'Mesaj',
            'required' => '*',
            'character_count' => 'karakter',
        ],
        'submit' => [
            'send' => 'Gönder',
            'sending' => 'Gönderiliyor...',
        ],
        'privacy_note' => 'Verileriniz yalnızca talebinizi işlemek için kullanılır',
    ],
    'messages' => [
        'success' => [
            'title' => 'Mesaj başarıyla gönderildi',
            'ticket_id' => 'Bilet kimliği:',
        ],
        'error' => [
            'title' => 'Gönderme hatası',
            'connection' => 'Bağlantı hatası. Lütfen tekrar deneyin.',
            'generic' => 'Bir hata oluştu.',
        ],
    ],
];
