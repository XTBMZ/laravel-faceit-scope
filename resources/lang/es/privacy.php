<?php
return [
    'title' => 'Política de Privacidad - Faceit Scope',
    'header' => [
        'title' => 'Política de Privacidad',
        'subtitle' => 'Extensión Faceit Scope',
        'last_updated' => 'Última actualización: 23 de julio de 2025',
    ],
    'introduction' => [
        'title' => '1. Introducción',
        'content' => 'Faceit Scope es una extensión de navegador que analiza partidas de CS2 de FACEIT para mostrar estadísticas y predicciones. Respetamos tu privacidad y estamos comprometidos a proteger tus datos personales.',
    ],
    'data_collected' => [
        'title' => '2. Datos recopilados',
        'temporary_data' => [
            'title' => '2.1 Datos procesados temporalmente (no almacenados)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Nombres de usuario públicos de FACEIT:',
                    'description' => 'Pseudónimos de juego ya mostrados públicamente en FACEIT, leídos temporalmente para análisis',
                ],
                'public_stats' => [
                    'title' => 'Estadísticas públicas de juego:',
                    'description' => 'K/D, tasa de victorias, mapas jugados (vía API pública de FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'IDs de partida:',
                    'description' => 'Extraídos de URL para identificar partidas a analizar',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Datos almacenados localmente (solo caché temporal)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Resultados de análisis:',
                    'description' => 'Almacenados máximo 5 minutos en tu dispositivo para evitar llamadas repetitivas a la API',
                ],
                'user_preferences' => [
                    'title' => 'Preferencias del usuario:',
                    'description' => 'Configuraciones de la extensión (notificaciones habilitadas/deshabilitadas)',
                ],
            ],
        ],
        'important_note' => 'Importante: No se recopilan ni retienen datos de identificación personal. Todos los datos procesados ya son públicos en FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Uso de datos',
        'description' => 'Los datos recopilados se usan exclusivamente para:',
        'items' => [
            'display_stats' => 'Mostrar estadísticas de jugadores en la interfaz de FACEIT',
            'predictions' => 'Calcular predicciones del equipo ganador',
            'map_recommendations' => 'Recomendar mejores/peores mapas por equipo',
            'performance' => 'Mejorar el rendimiento mediante caché temporal',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Compartir datos',
        'no_third_party' => [
            'title' => '4.1 No compartir con terceros',
            'items' => [
                'no_selling' => 'No vendemos ningún dato a terceros',
                'no_transfer' => 'No transferimos ningún dato personal',
                'local_analysis' => 'Todos los análisis se realizan localmente en tu navegador',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API de FACEIT',
            'items' => [
                'public_api' => 'La extensión solo usa la API pública oficial de FACEIT',
                'no_private_data' => 'No se recopilan datos privados o sensibles',
                'public_stats' => 'Todas las estadísticas utilizadas son de acceso público',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Seguridad y retención',
        'local_storage' => [
            'title' => '5.1 Solo almacenamiento local',
            'items' => [
                'local_only' => 'Todos los datos se almacenan localmente en tu dispositivo',
                'no_server_transmission' => 'No se transmiten datos a nuestros servidores',
                'auto_delete' => 'El caché se elimina automáticamente después de 5 minutos',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Acceso limitado',
            'items' => [
                'faceit_only' => 'La extensión solo accede a páginas de FACEIT que visitas',
                'no_other_access' => 'Sin acceso a otros sitios web o datos personales',
                'no_tracking' => 'Sin seguimiento de tu navegación',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Tus derechos',
        'data_control' => [
            'title' => '6.1 Control de datos',
            'items' => [
                'clear_cache' => 'Puedes limpiar el caché en cualquier momento vía el popup de la extensión',
                'uninstall' => 'Puedes desinstalar la extensión para eliminar todos los datos',
                'disable_notifications' => 'Puedes deshabilitar notificaciones en configuraciones',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Datos públicos',
            'items' => [
                'already_public' => 'Todos los datos analizados ya son públicos en FACEIT',
                'no_private_info' => 'La extensión no revela ninguna información privada',
                'no_personal_data' => 'No se recopilan datos de identificación personal',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies y tecnologías de seguimiento',
        'description' => 'La extensión Faceit Scope:',
        'does_not_use' => [
            'title' => 'No utiliza:',
            'items' => [
                'no_cookies' => 'Sin cookies',
                'no_ad_tracking' => 'Sin seguimiento publicitario',
                'no_behavioral_analysis' => 'Sin análisis de comportamiento',
            ],
        ],
        'uses_only' => [
            'title' => 'Solo utiliza:',
            'items' => [
                'local_storage' => 'Almacenamiento local del navegador',
                'temp_cache' => 'Caché temporal (máximo 5 minutos)',
                'public_api' => 'API pública de FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Actualizaciones de esta política',
        'content' => 'Podemos actualizar esta política de privacidad. Los cambios se publicarán en esta página con una nueva fecha y se notificarán vía actualización de extensión si es necesario.',
    ],
    'contact' => [
        'title' => '9. Contacto',
        'description' => 'Para cualquier pregunta sobre esta política de privacidad:',
        'website' => 'Sitio web:',
        'email' => 'Email:',
    ],
    'compliance' => [
        'title' => '10. Cumplimiento regulatorio',
        'description' => 'Esta extensión cumple con:',
        'items' => [
            'gdpr' => 'El Reglamento General de Protección de Datos (GDPR)',
            'chrome_store' => 'Políticas de Chrome Web Store',
            'faceit_terms' => 'Términos de uso de la API de FACEIT',
        ],
    ],
];
