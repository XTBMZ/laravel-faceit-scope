<?php
return [
    'title' => 'Faceit Scope - Analiza tus estadísticas de FACEIT',
    'hero' => [
        'subtitle' => 'Analiza tu rendimiento en FACEIT con algoritmos avanzados e inteligencia artificial. Descubre tus fortalezas y mejórate.',
        'features' => [
            'detailed_stats' => 'Estadísticas detalladas',
            'artificial_intelligence' => 'Inteligencia artificial',
            'predictive_analysis' => 'Análisis predictivo',
        ]
    ],
    'search' => [
        'title' => 'Iniciar el análisis',
        'subtitle' => 'Busca un jugador o analiza una partida para descubrir insights detallados',
        'player' => [
            'title' => 'Buscar un jugador',
            'description' => 'Analizar el rendimiento de un jugador',
            'placeholder' => 'Nombre del jugador de FACEIT...',
            'button' => 'Buscar',
            'loading' => 'Buscando...',
        ],
        'match' => [
            'title' => 'Analizar una partida',
            'description' => 'Predicciones de IA y análisis en profundidad',
            'placeholder' => 'ID de partida o URL...',
            'button' => 'Analizar',
            'loading' => 'Analizando...',
        ],
        'errors' => [
            'empty_player' => 'Por favor ingresa un nombre de jugador',
            'empty_match' => 'Por favor ingresa un ID de partida o URL',
            'player_not_found' => 'El jugador ":player" no fue encontrado en FACEIT',
            'no_cs_stats' => 'El jugador ":player" nunca ha jugado CS2/CS:GO en FACEIT',
            'no_stats_available' => 'No hay estadísticas disponibles para ":player"',
            'match_not_found' => 'No se encontró ninguna partida con este ID o URL',
            'invalid_format' => 'Formato de ID o URL de partida inválido. Ejemplos válidos:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Demasiadas solicitudes. Por favor espera un momento.',
            'access_forbidden' => 'Acceso prohibido. Problema con la clave API.',
            'generic_player' => 'Error buscando ":player". Verifica tu conexión.',
            'generic_match' => 'Error recuperando partida. Verifica el ID o URL.',
        ]
    ],
    'features' => [
        'title' => 'Características',
        'subtitle' => 'Herramientas poderosas para analizar y mejorar tu rendimiento',
        'advanced_stats' => [
            'title' => 'Estadísticas avanzadas',
            'description' => 'Analiza tu rendimiento por mapa, rastrea tu K/D, headshots y descubre tus mejores/peores mapas con nuestros algoritmos.',
        ],
        'ai' => [
            'title' => 'Inteligencia artificial',
            'description' => 'Predicciones de partidas, identificación de jugadores clave, análisis de roles y recomendaciones personalizadas basadas en tus datos.',
        ],
        'lobby_analysis' => [
            'title' => 'Análisis de lobby',
            'description' => 'Descubre la composición de partidas, fortalezas y obtén predicciones detalladas sobre los resultados de las partidas.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Cómo funciona',
        'subtitle' => 'Un enfoque científico para el análisis de rendimiento de FACEIT',
        'steps' => [
            'data_collection' => [
                'title' => 'Recolección de datos',
                'description' => 'Utilizamos exclusivamente la API oficial de FACEIT para recuperar todas tus estadísticas de manera transparente y legal.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Análisis algorítmico',
                'description' => 'Nuestros algoritmos analizan tus datos con normalización, ponderación y cálculos de confianza para insights precisos.',
            ],
            'personalized_insights' => [
                'title' => 'Insights personalizados',
                'description' => 'Recibe análisis detallados, predicciones y recomendaciones para mejorar tu rendimiento de juego.',
            ]
        ]
    ]
];
