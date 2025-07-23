<?php
return [
    'title' => 'Gizlilik Politikası - Faceit Scope',
    'header' => [
        'title' => 'Gizlilik Politikası',
        'subtitle' => 'Faceit Scope uzantısı',
        'last_updated' => 'Son güncelleme: 23 Temmuz 2025',
    ],
    'introduction' => [
        'title' => '1. Giriş',
        'content' => 'Faceit Scope, istatistikleri ve tahminleri göstermek için FACEIT\'teki CS2 maçlarını analiz eden bir tarayıcı uzantısıdır. Gizliliğinize saygı duyuyor ve kişisel verilerinizi korumaya kendimizi adamış durumdayız.',
    ],
    'data_collected' => [
        'title' => '2. Toplanan Veriler',
        'temporary_data' => [
            'title' => '2.1 Geçici olarak işlenen veriler (saklanmaz)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'FACEIT genel kullanıcı adları:',
                    'description' => 'FACEIT\'te zaten herkese açık olan oyun takma adları, analiz için geçici olarak okunur',
                ],
                'public_stats' => [
                    'title' => 'Genel oyun istatistikleri:',
                    'description' => 'K/D, kazanma oranı, oynanan haritalar (FACEIT genel API\'si aracılığıyla)',
                ],
                'match_ids' => [
                    'title' => 'Maç kimlikleri:',
                    'description' => 'Analiz edilecek maçı tanımlamak için URL\'den çıkarılır',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Yerel olarak saklanan veriler (yalnızca geçici önbellek)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analiz sonuçları:',
                    'description' => 'Tekrarlanan API çağrılarını önlemek için cihazınızda en fazla 5 dakika saklanır',
                ],
                'user_preferences' => [
                    'title' => 'Kullanıcı tercihleri:',
                    'description' => 'Uzantı ayarları (bildirimleri etkinleştir/devre dışı bırak)',
                ],
            ],
        ],
        'important_note' => 'Önemli: Kişisel tanımlayıcı veriler toplanmaz veya kaydedilmez. İşlenen tüm veriler FACEIT\'te zaten herkese açıktır.',
    ],
    'data_usage' => [
        'title' => '3. Veri Kullanımı',
        'description' => 'Toplanan veriler yalnızca şunlar için kullanılır:',
        'items' => [
            'display_stats' => 'FACEIT arayüzünde oyuncu istatistiklerini görüntüleme',
            'predictions' => 'Kazanan takım tahminlerini hesaplama',
            'map_recommendations' => 'Takımlar için en iyi/en kötü harita önerileri',
            'performance' => 'Geçici önbellekleme ile performansı artırma',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Veri Paylaşımı',
        'no_third_party' => [
            'title' => '4.1 Üçüncü taraflarla paylaşım yok',
            'items' => [
                'no_selling' => 'Hiçbir veriyi üçüncü taraflara satmıyoruz',
                'no_transfer' => 'Hiçbir kişisel veri aktarmıyoruz',
                'local_analysis' => 'Tüm analizler tarayıcınızda yerel olarak gerçekleştirilir',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'Uzantı yalnızca resmi FACEIT genel API\'sini kullanır',
                'no_private_data' => 'Özel veya hassas veri toplanmaz',
                'public_stats' => 'Kullanılan tüm istatistikler herkese açık erişilebilir',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Güvenlik ve Saklama',
        'local_storage' => [
            'title' => '5.1 Yalnızca yerel depolama',
            'items' => [
                'local_only' => 'Tüm veriler cihazınızda yerel olarak saklanır',
                'no_server_transmission' => 'Sunucularımıza veri aktarımı yoktur',
                'auto_delete' => 'Önbellek 5 dakika sonra otomatik olarak silinir',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Sınırlı erişim',
            'items' => [
                'faceit_only' => 'Uzantı yalnızca ziyaret ettiğiniz FACEIT sayfalarına erişir',
                'no_other_access' => 'Diğer web sitelerine veya kişisel verilere erişim yoktur',
                'no_tracking' => 'Tarama takibi yoktur',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Haklarınız',
        'data_control' => [
            'title' => '6.1 Veri kontrolü',
            'items' => [
                'clear_cache' => 'Uzantı popup\'ından istediğiniz zaman önbelleği temizleyebilirsiniz',
                'uninstall' => 'Tüm verileri silmek için uzantıyı kaldırabilirsiniz',
                'disable_notifications' => 'Ayarlardan bildirimleri devre dışı bırakabilirsiniz',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Genel veriler',
            'items' => [
                'already_public' => 'Analiz edilen tüm veriler FACEIT\'te zaten herkese açıktır',
                'no_private_info' => 'Uzantı hiçbir özel bilgiyi ifşa etmez',
                'no_personal_data' => 'Kişisel tanımlayıcı veri toplanmaz',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Çerezler ve İzleme Teknolojileri',
        'description' => 'Faceit Scope uzantısı:',
        'does_not_use' => [
            'title' => 'Kullanmaz:',
            'items' => [
                'no_cookies' => 'Çerez yok',
                'no_ad_tracking' => 'Reklam izleme yok',
                'no_behavioral_analysis' => 'Davranışsal analiz yok',
            ],
        ],
        'uses_only' => [
            'title' => 'Yalnızca kullanır:',
            'items' => [
                'local_storage' => 'Tarayıcı yerel depolaması',
                'temp_cache' => 'Geçici önbellek (en fazla 5 dakika)',
                'public_api' => 'FACEIT genel API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Bu Politikanın Güncellemeleri',
        'content' => 'Bu gizlilik politikasını güncelleyebiliriz. Değişiklikler bu sayfada yayınlanacak ve gerekirse uzantı güncellemesi ile bilgilendirileceksiniz.',
    ],
    'contact' => [
        'title' => '9. İletişim',
        'description' => 'Bu gizlilik politikası hakkında herhangi bir sorunuz varsa:',
        'website' => 'Web sitesi:',
        'email' => 'E-posta:',
    ],
    'compliance' => [
        'title' => '10. Düzenleyici Uyumluluk',
        'description' => 'Bu uzantı şunlara uygundur:',
        'items' => [
            'gdpr' => 'Genel Veri Koruma Yönetmeliği (GDPR)',
            'chrome_store' => 'Chrome Web Mağazası politikaları',
            'faceit_terms' => 'FACEIT API kullanım şartları',
        ],
    ],
];
