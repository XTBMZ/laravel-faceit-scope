<?php
return [
    'title' => 'Faceit Scope - FACEIT İstatistiklerinizi Analiz Edin',
    'hero' => [
        'subtitle' => 'FACEIT\'teki performansınızı gelişmiş algoritmalar ve yapay zeka ile analiz edin. Güçlü yönlerinizi keşfedin ve becerilerinizi geliştirin.',
        'features' => [
            'detailed_stats' => 'Detaylı İstatistikler',
            'artificial_intelligence' => 'Yapay Zeka',
            'predictive_analysis' => 'Öngörücü Analiz',
        ]
    ],
    'search' => [
        'title' => 'Analize Başla',
        'subtitle' => 'Detaylı içgörüler keşfetmek için oyuncu arayın veya maç analiz edin',
        'player' => [
            'title' => 'Oyuncu Ara',
            'description' => 'Oyuncu performansını analiz et',
            'placeholder' => 'FACEIT oyuncu adı...',
            'button' => 'Ara',
            'loading' => 'Aranıyor...',
        ],
        'match' => [
            'title' => 'Maç Analiz Et',
            'description' => 'AI tahminleri ve derinlemesine analiz',
            'placeholder' => 'Maç ID veya URL...',
            'button' => 'Analiz Et',
            'loading' => 'Analiz ediliyor...',
        ],
        'errors' => [
            'empty_player' => 'Lütfen bir oyuncu adı girin',
            'empty_match' => 'Lütfen bir maç ID veya URL girin',
            'player_not_found' => 'FACEIT\'te ":player" oyuncusu bulunamadı',
            'no_cs_stats' => '":player" oyuncusu FACEIT\'te hiç CS2/CS:GO oynamamış',
            'no_stats_available' => '":player" için istatistik mevcut değil',
            'match_not_found' => 'Bu ID veya URL için maç bulunamadı',
            'invalid_format' => 'Maç ID veya URL formatı geçersiz. Geçerli örnekler:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Çok fazla istek. Lütfen bekleyin.',
            'access_forbidden' => 'Erişim yasak. API anahtarı problemi.',
            'generic_player' => '":player" arama hatası. Lütfen bağlantınızı kontrol edin.',
            'generic_match' => 'Maç alma hatası. Lütfen ID veya URL\'yi kontrol edin.',
        ]
    ],
    'features' => [
        'title' => 'Özellikler',
        'subtitle' => 'Performansınızı analiz etmek ve geliştirmek için güçlü araçlar',
        'advanced_stats' => [
            'title' => 'Gelişmiş İstatistikler',
            'description' => 'Performansınızı haritalara göre analiz edin, K/D\'nizi takip edin, kafadan vuruşlarınızı izleyin ve algoritmalarımızla en iyi/en kötü haritalarınızı keşfedin.',
        ],
        'ai' => [
            'title' => 'Yapay Zeka',
            'description' => 'Maç tahminleri, anahtar oyuncu tanımlama, rol analizi ve verilerinize dayalı kişiselleştirilmiş öneriler.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobi Analizi',
            'description' => 'Maç kompozisyonunu keşfedin, avantajları görün ve detaylı maç sonucu tahminleri alın.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Nasıl Çalışır',
        'subtitle' => 'FACEIT performans analizine bilimsel yaklaşım',
        'steps' => [
            'data_collection' => [
                'title' => 'Veri Toplama',
                'description' => 'Tüm istatistiklerinizi şeffaf ve yasal bir şekilde almak için yalnızca resmi FACEIT API\'sini kullanıyoruz.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algoritmik Analiz',
                'description' => 'Algoritmalarımız kesin içgörüler için normalleştirme, ağırlıklandırma ve güven hesaplamaları ile verilerinizi analiz eder.',
            ],
            'personalized_insights' => [
                'title' => 'Kişiselleştirilmiş İçgörüler',
                'description' => 'Oyun performansınızı artırmak için detaylı analizler, tahminler ve öneriler alın.',
            ]
        ]
    ]
];
