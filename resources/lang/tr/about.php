<?php
return [
    'title' => 'Hakkımızda - Faceit Scope',
    'hero' => [
        'title' => 'Hakkımızda',
        'subtitle' => 'Faceit Scope, FACEIT\'teki performansınızı analiz etmek için gelişmiş algoritmalar ve yapay zeka kullanır. Bu, tutkuyla geliştirilen bir projedir.',
    ],
    'project' => [
        'title' => 'Proje Hakkında',
        'description_1' => 'Performansın derinlemesine analizine olanak tanır.',
        'description_2' => 'Tamamen',
        'description_3' => 'tarafından geliştirilen bu proje, tüm verilerinizi şeffaf ve yasal bir şekilde almak için yalnızca resmi FACEIT API\'sini kullanır.',
        'description_4' => 'Her şey doğrudan FACEIT sunucularından gelir ve özel algoritmalarımız tarafından analiz edilir.',
        'stats' => [
            'developer' => 'Geliştirici',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Nasıl Çalışır',
        'subtitle' => 'Gelişmiş algoritmalar FACEIT verilerinizi analiz ederek size kesin içgörüler sunar',
        'pis' => [
            'title' => 'Oyuncu Etki Puanı (PIS)',
            'combat' => [
                'title' => 'Savaş (35%)',
                'description' => 'K/D, ADR ve kafadan vuruş oranı, logaritmik normalleştirme ile',
            ],
            'game_sense' => [
                'title' => 'Oyun Zekası (25%)',
                'description' => 'Giriş kabiliyeti, clutch ve keskin nişancılık, gelişmiş birleşim ile',
            ],
            'utility' => [
                'title' => 'Destek (15%)',
                'description' => 'Destek ve destek araç kullanımı, ağırlıklı verimlilik ile',
            ],
            'consistency' => [
                'title' => 'Tutarlılık + Deneyim (25%)',
                'description' => 'Kazanma oranı, kazanma serisi ve veri güvenilirliği',
            ],
            'level_coefficient' => [
                'title' => 'Kritik Seviye Katsayısı:',
                'description' => '10. seviyede 1.0 K/D olan bir oyuncu, 2. seviyede 1.5 K/D olan birinden daha yüksek puan alır çünkü daha güçlü rakiplerle karşılaşır.',
            ],
        ],
        'roles' => [
            'title' => 'Akıllı Rol Atama',
            'calculations_title' => 'Rol Puanı Hesaplaması',
            'priority_title' => 'Atama Önceliği',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Belirli kriterler: Giriş oranı > %25 ve Giriş başarısı > %55',
            ],
            'support' => [
                'title' => 'Destek',
                'criteria' => 'Belirli kriterler: Flash > 0.4/round ve Flash başarısı > %50',
            ],
            'awper' => [
                'title' => 'AWP\'er',
                'criteria' => 'Belirli kriterler: Keskin nişancı oranı > %15',
            ],
            'priority_items' => [
                'awper' => 'AWP\'er (eğer keskin nişancı > %15)',
                'entry' => 'Entry Fragger (eğer giriş > %25 + başarı > %55)',
                'support' => 'Destek (eğer flash > 0.4 + başarı > %50)',
                'clutcher' => 'Clutch Master (eğer 1v1 > %40)',
                'fragger' => 'Fragger (eğer K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (varsayılan, diğer kriterler yoksa)',
            ],
        ],
        'maps' => [
            'title' => 'Harita Analiz Algoritması',
            'normalization' => [
                'title' => 'Logaritmik Normalleştirme',
            ],
            'weighting' => [
                'title' => 'Gelişmiş Ağırlıklandırma',
                'win_rate' => 'Kazanma oranı:',
                'consistency' => 'Tutarlılık:',
            ],
            'reliability' => [
                'title' => 'Güvenilirlik Faktörü',
            ],
        ],
        'predictions' => [
            'title' => 'Maç Tahminleri',
            'team_strength' => [
                'title' => 'Takım Gücü',
                'average_score' => [
                    'title' => 'Ağırlıklı Ortalama Puan',
                    'description' => '5 PIS puanının ortalaması + rol dengesi bonusu',
                ],
                'role_balance' => [
                    'title' => 'Rol Dengesi',
                    'description' => 'Entry fragger + destek + AWP\'er + clutcher + fragger\'dan oluşan bir takım, 5 fragger\'dan oluşan takıma göre önemli bonus alır.',
                ],
            ],
            'probability' => [
                'title' => 'Olasılık Hesaplaması',
                'match_winner' => [
                    'title' => 'Maç Galibi',
                    'description' => 'Güç farkı ne kadar büyükse, tahmin o kadar doğru',
                ],
                'predicted_mvp' => [
                    'title' => 'Tahmini MVP',
                    'description' => 'Oyuncuya sahip',
                    'description_end' => '10 katılımcı arasında tahmini MVP olacak',
                    'highest_score' => 'en yüksek PIS puanı',
                ],
                'confidence' => [
                    'title' => 'Güven',
                    'description' => 'Güç farkına göre: Çok yüksek (>3), Yüksek (>2), Orta (>1), Düşük (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'İletişim',
        'subtitle' => 'Bu, tutkuyla geliştirilen bir projedir. Geri bildirim veya önerileriniz için benimle iletişime geçmekten çekinmeyin.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope, FACEIT Ltd. ile bağlantılı değildir. Bu proje, hizmet şartlarına uygun olarak FACEIT genel API\'sini kullanır. Tahmin algoritmaları istatistiksel analize dayanır ve maç sonuçları garanti edilmez.',
    ],
];
