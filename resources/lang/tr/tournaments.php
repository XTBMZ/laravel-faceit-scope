<?php
return [
    'title' => 'CS2 Turnuvaları - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2 Turnuvaları',
        'subtitle' => 'Resmi FACEIT CS2 turnuvalarını keşfedin, en iyi e-spor etkinliklerini gerçek zamanlı takip edin',
        'features' => [
            'ongoing' => 'Devam eden turnuvalar',
            'upcoming' => 'Yaklaşan etkinlikler',
            'premium' => 'Premium turnuvalar',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Devam Eden',
            'upcoming' => 'Yaklaşan',
            'past' => 'Geçmiş',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Turnuva ara...',
            'button' => 'Ara',
        ],
        'stats' => [
            'ongoing' => 'Devam eden',
            'upcoming' => 'Yaklaşan',
            'prize_pools' => 'Ödül havuzları',
            'participants' => 'Katılımcılar',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Devam Eden',
            'upcoming' => 'Yaklaşan',
            'finished' => 'Bitti',
            'cancelled' => 'İptal Edildi',
        ],
        'info' => [
            'participants' => 'Katılımcılar',
            'prize_pool' => 'Ödül Havuzu',
            'registrations' => 'Kayıtlar',
            'organizer' => 'Organizatör',
            'status' => 'Durum',
            'region' => 'Bölge',
            'level' => 'Seviye',
            'slots' => 'Slotlar',
        ],
        'actions' => [
            'details' => 'Detaylar',
            'view_faceit' => 'FACEIT\'te Görüntüle',
            'view_matches' => 'Maçları Görüntüle',
            'results' => 'Sonuçlar',
            'close' => 'Kapat',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Detaylar yükleniyor...',
            'subtitle' => 'Turnuva bilgileri alınıyor',
        ],
        'error' => [
            'title' => 'Yükleme hatası',
            'subtitle' => 'Turnuva detayları yüklenemedi',
        ],
        'sections' => [
            'description' => 'Açıklama',
            'information' => 'Bilgiler',
            'matches' => 'Turnuva Maçları',
            'results' => 'Turnuva Sonuçları',
            'default_description' => 'Bu turnuva, FACEIT tarafından düzenlenen resmi CS2 rekabetinin bir parçasıdır.',
        ],
        'matches' => [
            'loading' => 'Maçlar yükleniyor...',
            'no_matches' => 'Bu turnuva için mevcut maç yok',
            'error' => 'Maçları yükleme hatası',
            'status' => [
                'finished' => 'Bitti',
                'ongoing' => 'Devam eden',
                'upcoming' => 'Yaklaşan',
            ]
        ],
        'results' => [
            'loading' => 'Sonuçlar yükleniyor...',
            'no_results' => 'Bu turnuva için mevcut sonuç yok',
            'error' => 'Sonuçları yükleme hatası',
            'position' => 'Pozisyon',
        ]
    ],
    'pagination' => [
        'previous' => 'Önceki',
        'next' => 'Sonraki',
        'page' => 'Sayfa',
    ],
    'empty_state' => [
        'title' => 'Turnuva bulunamadı',
        'subtitle' => 'Filtrelerinizi değiştirmeyi deneyin veya başka bir şey arayın',
        'reset_button' => 'Filtreleri sıfırla',
    ],
    'errors' => [
        'search' => 'Arama hatası',
        'loading' => 'Turnuvaları yükleme hatası',
        'api' => 'API hatası',
        'network' => 'Bağlantı hatası',
    ]
];
