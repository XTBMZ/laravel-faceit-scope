<?php
return [
    'title' => 'Rankings Globales de CS2 - Faceit Scope',
    'hero' => [
        'title' => 'Rankings de CS2',
        'subtitle' => 'Mejores jugadores en tiempo real vía API de FACEIT',
    ],
    'stats' => [
        'players' => 'Jugadores',
        'average_elo' => 'ELO Promedio',
        'country' => 'País',
        'level' => 'Nivel',
    ],
    'filters' => [
        'region' => 'Región',
        'country' => 'País',
        'limit' => 'Límite',
        'refresh' => 'Actualizar',
        'search' => 'Buscar',
        'regions' => [
            'EU' => '🌍 Europa',
            'NA' => '🌎 América del Norte',
            'SA' => '🌎 América del Sur',
            'AS' => '🌏 Asia',
            'AF' => '🌍 África',
            'OC' => '🌏 Oceanía',
        ],
        'countries' => [
            'all' => 'Todos',
        ],
        'limits' => [
            'top20' => 'Top 20',
            'top50' => 'Top 50',
            'top100' => 'Top 100',
        ],
        'refreshing' => 'Actualizando...',
        'close' => 'Cerrar',
    ],
    'search' => [
        'title' => 'Buscar un jugador',
        'placeholder' => 'Nombre del jugador de FACEIT...',
        'button' => 'Buscar',
        'searching' => 'Buscando...',
        'searching_for' => 'Buscando :player...',
        'errors' => [
            'empty_name' => 'Por favor ingresa un nombre de jugador',
            'not_found' => 'Jugador ":player" no encontrado',
            'no_cs2_profile' => 'El jugador ":player" no tiene perfil de CS2',
            'timeout' => 'Búsqueda muy lenta, por favor inténtalo de nuevo...',
        ],
    ],
    'loading' => [
        'title' => 'Cargando...',
        'progress' => 'Conectando a la API de FACEIT',
        'players_enriched' => ':count jugadores enriquecidos...',
        'details' => 'Cargando...',
    ],
    'error' => [
        'title' => 'Error de carga',
        'default_message' => 'Ocurrió un error',
        'retry' => 'Reintentar',
        'no_players' => 'No se encontraron jugadores en este ranking',
    ],
    'leaderboard' => [
        'title' => 'Ranking Global',
        'updated_now' => 'Actualizado ahora',
        'updated_on' => 'Actualizado el :date a las :time',
        'table' => [
            'rank' => '#',
            'player' => 'Jugador',
            'stats' => '',
            'elo' => 'ELO',
            'level' => 'Nivel',
            'form' => 'Forma',
            'actions' => 'Acciones',
        ],
        'pagination' => [
            'previous' => 'Anterior',
            'next' => 'Siguiente',
            'page' => 'Página :page',
            'players' => 'Jugadores :start-:end',
        ],
        'region_names' => [
            'EU' => 'Europa',
            'NA' => 'América del Norte',
            'SA' => 'América del Sur',
            'AS' => 'Asia',
            'AF' => 'África',
            'OC' => 'Oceanía',
        ],
        'country_names' => [
            'FR' => 'Francia',
            'DE' => 'Alemania',
            'GB' => 'Reino Unido',
            'ES' => 'España',
            'IT' => 'Italia',
            'US' => 'Estados Unidos',
            'CA' => 'Canadá',
            'BR' => 'Brasil',
            'RU' => 'Rusia',
            'PL' => 'Polonia',
            'SE' => 'Suecia',
            'DK' => 'Dinamarca',
            'NO' => 'Noruega',
            'FI' => 'Finlandia',
            'NL' => 'Países Bajos',
            'BE' => 'Bélgica',
            'CH' => 'Suiza',
            'AT' => 'Austria',
            'CZ' => 'República Checa',
            'UA' => 'Ucrania',
            'TR' => 'Turquía',
        ],
    ],
    'player' => [
        'position_region' => 'Posición :region',
        'stats_button' => 'Estadísticas',
        'compare_button' => 'Comparar',
        'view_stats' => 'Ver estadísticas',
        'private_stats' => 'Privado',
        'level_short' => 'Niv :level',
    ],
    'form' => [
        'excellent' => 'Excelente',
        'good' => 'Bueno',
        'average' => 'Promedio',
        'poor' => 'Pobre',
        'unknown' => 'Desconocido',
    ],
];
