<?php
return [
    'title' => 'Oyuncu Karşılaştırması - Faceit Scope',
    'hero' => [
        'title' => 'Oyuncu Karşılaştırması',
        'subtitle' => 'İki CS2 oyuncusunun performansını karşılaştırın',
    ],
    'search' => [
        'player1' => 'Oyuncu 1',
        'player2' => 'Oyuncu 2',
        'placeholder' => 'Faceit kullanıcı adı...',
        'button' => 'Karşılaştırmayı Başlat',
        'loading' => 'Analiz Ediliyor',
        'loading_text' => 'Oyuncular karşılaştırılıyor',
        'errors' => [
            'both_players' => 'Lütfen iki kullanıcı adı girin',
            'different_players' => 'Lütfen iki farklı kullanıcı adı girin',
        ]
    ],
    'loading' => [
        'title' => 'Analiz Ediliyor',
        'messages' => [
            'player1_data' => 'Oyuncu 1 verisi alınıyor',
            'player2_data' => 'Oyuncu 2 verisi alınıyor',
            'analyzing_stats' => 'İstatistikler analiz ediliyor',
            'calculating_scores' => 'Performans puanları hesaplanıyor',
            'comparing_roles' => 'Oyun rolleri karşılaştırılıyor',
            'generating_report' => 'Son rapor oluşturuluyor'
        ]
    ],
    'tabs' => [
        'overview' => 'Genel Bakış',
        'detailed' => 'Detaylı İstatistikler',
        'maps' => 'Haritalar'
    ],
    'winner' => [
        'analysis_complete' => 'Analiz tamamlandı',
        'wins_analysis' => ':winner AI analizini kazandı',
        'confidence' => 'Güven: %:percentage',
        'performance_score' => 'Performans Puanı',
        'matches' => 'Maçlar'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Performans Puanları',
            'elo_impact' => 'ELO Etkisi',
            'combat_performance' => 'Savaş Performansı',
            'experience' => 'Deneyim',
            'advanced_stats' => 'Gelişmiş İstatistikler'
        ],
        'key_stats' => [
            'title' => 'Anahtar İstatistikler',
            'kd_ratio' => 'K/D Oranı',
            'win_rate' => 'Kazanma Oranı',
            'headshots' => 'Kafadan Vuruşlar',
            'adr' => 'ADR',
            'entry_success' => 'Giriş Başarısı',
            'clutch_1v1' => '1v1 Clutch'
        ],
        'calculation_info' => [
            'title' => 'Puanlar Nasıl Hesaplanır?',
            'elo_impact' => [
                'title' => 'ELO Etkisi (35%)',
                'description' => 'ELO seviyesi en önemli faktördür çünkü aynı güçteki rakiplere karşı oyun seviyesini doğrudan yansıtır.'
            ],
            'combat_performance' => [
                'title' => 'Savaş Performansı (25%)',
                'description' => 'Savaş etkinliğini değerlendirmek için K/D, kazanma oranı, ADR ve Faceit seviyesini birleştirir.'
            ],
            'experience' => [
                'title' => 'Deneyim (20%)',
                'description' => 'Oynanan maç sayısı, birikmiş deneyime dayalı çarpan.'
            ],
            'advanced_stats' => [
                'title' => 'Gelişmiş İstatistikler (20%)',
                'description' => 'Derinlemesine oyun tarzı analizi için kafadan vuruş, giriş ve clutch yetenekleri.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Genel Performans',
                'stats' => [
                    'total_matches' => 'Toplam Maçlar',
                    'win_rate' => 'Kazanma Oranı',
                    'wins' => 'Zaferler',
                    'avg_kd' => 'Ortalama K/D Oranı',
                    'adr' => 'ADR (Hasar/Round)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Savaş ve Hassasiyet',
                'stats' => [
                    'avg_headshots' => 'Ortalama Kafadan Vuruşlar',
                    'total_headshots' => 'Toplam Kafadan Vuruşlar',
                    'total_kills' => 'Öldürmeler (Genişletilmiş istatistik)',
                    'total_damage' => 'Toplam Hasar'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Giriş Fragging',
                'stats' => [
                    'entry_rate' => 'Giriş Oranı',
                    'entry_success' => 'Giriş Başarı Oranı',
                    'total_entries' => 'Toplam Denemeler',
                    'successful_entries' => 'Başarılı Girişler'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch Durumları',
                'stats' => [
                    '1v1_win_rate' => '1v1 Kazanma Oranı',
                    '1v2_win_rate' => '1v2 Kazanma Oranı',
                    '1v1_situations' => '1v1 Durumları',
                    '1v1_wins' => '1v1 Zaferleri',
                    '1v2_situations' => '1v2 Durumları',
                    '1v2_wins' => '1v2 Zaferleri'
                ]
            ],
            'utility_support' => [
                'title' => 'Destek ve Yardım',
                'stats' => [
                    'flash_success' => 'Flash Başarı Oranı',
                    'flashes_per_round' => 'Flash/Round',
                    'total_flashes' => 'Toplam Flash',
                    'successful_flashes' => 'Başarılı Flash',
                    'enemies_flashed_per_round' => 'Flash Yiyen Düşman/Round',
                    'total_enemies_flashed' => 'Toplam Flash Yiyen Düşman',
                    'utility_success' => 'Destek Başarı Oranı',
                    'utility_damage_per_round' => 'Destek Hasarı/Round',
                    'total_utility_damage' => 'Toplam Destek Hasarı'
                ]
            ],
            'sniper_special' => [
                'title' => 'Keskin Nişancılık ve Özel Silahlar',
                'stats' => [
                    'sniper_kill_rate' => 'Keskin Nişancı Öldürme Oranı',
                    'sniper_kills_per_round' => 'Keskin Nişancı Öldürme/Round',
                    'total_sniper_kills' => 'Toplam Keskin Nişancı Öldürmeleri'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Seriler ve Tutarlılık',
                'stats' => [
                    'current_streak' => 'Mevcut Seri',
                    'longest_streak' => 'En Uzun Seri'
                ]
            ]
        ],
        'legend' => 'Yeşil değerler o istatistikte daha iyi performans gösteren oyuncuyu gösterir'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Ortak Harita Yok',
            'description' => 'İki oyuncunun da yeterli veriye sahip olduğu ortak harita yok.'
        ],
        'dominates' => ':player baskın',
        'win_rate' => 'Kazanma oranı (:matches maç)',
        'kd_ratio' => 'K/D Oranı',
        'headshots' => 'Kafadan Vuruşlar',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Harita Özeti',
            'maps_dominated' => 'Baskın Olunan Haritalar',
            'best_map' => 'En İyi Harita',
            'none' => 'Yok'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Noktaları ele geçirme uzmanı'
        ],
        'support' => [
            'name' => 'Destek',
            'description' => 'Takım desteği ustası'
        ],
        'clutcher' => [
            'name' => 'Clutch Master',
            'description' => 'Zor durumlar uzmanı'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Eliminasyon uzmanı'
        ],
        'versatile' => [
            'name' => 'Çok Yönlü',
            'description' => 'Dengeli oyuncu'
        ]
    ],
    'error' => [
        'title' => 'Hata',
        'default_message' => 'Karşılaştırma sırasında hata oluştu',
        'retry' => 'Tekrar dene',
        'player_not_found' => 'Oyuncu ":player" bulunamadı',
        'stats_error' => 'İstatistik alma hatası: :status'
    ]
];
