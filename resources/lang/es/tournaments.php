<?php
return [
    'title' => 'Campeonatos de CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Campeonatos de CS2',
        'subtitle' => 'Descubre los campeonatos oficiales de CS2 de FACEIT y sigue los mejores eventos de eSports en tiempo real',
        'features' => [
            'ongoing' => 'Campeonatos en curso',
            'upcoming' => 'Próximos eventos',
            'premium' => 'Campeonatos premium',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'En curso',
            'upcoming' => 'Próximos',
            'past' => 'Terminados',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Buscar un campeonato...',
            'button' => 'Buscar',
        ],
        'stats' => [
            'ongoing' => 'En curso',
            'upcoming' => 'Próximos',
            'prize_pools' => 'Bolsas de premios',
            'participants' => 'Participantes',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'EN CURSO',
            'upcoming' => 'PRÓXIMO',
            'finished' => 'TERMINADO',
            'cancelled' => 'CANCELADO',
        ],
        'info' => [
            'participants' => 'Participantes',
            'prize_pool' => 'Bolsa de premios',
            'registrations' => 'Inscripciones',
            'organizer' => 'Organizador',
            'status' => 'Estado',
            'region' => 'Región',
            'level' => 'Nivel',
            'slots' => 'Plazas',
        ],
        'actions' => [
            'details' => 'Detalles',
            'view_faceit' => 'Ver en FACEIT',
            'view_matches' => 'Ver partidas',
            'results' => 'Resultados',
            'close' => 'Cerrar',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Cargando detalles...',
            'subtitle' => 'Recuperando información del campeonato',
        ],
        'error' => [
            'title' => 'Error de carga',
            'subtitle' => 'No se pudieron cargar los detalles del campeonato',
        ],
        'sections' => [
            'description' => 'Descripción',
            'information' => 'Información',
            'matches' => 'Partidas del campeonato',
            'results' => 'Resultados del campeonato',
            'default_description' => 'Este campeonato forma parte de las competiciones oficiales de CS2 organizadas en FACEIT.',
        ],
        'matches' => [
            'loading' => 'Cargando partidas...',
            'no_matches' => 'No hay partidas disponibles para este campeonato',
            'error' => 'Error cargando partidas',
            'status' => [
                'finished' => 'Terminado',
                'ongoing' => 'En curso',
                'upcoming' => 'Próximo',
            ]
        ],
        'results' => [
            'loading' => 'Cargando resultados...',
            'no_results' => 'No hay resultados disponibles para este campeonato',
            'error' => 'Error cargando resultados',
            'position' => 'Posición',
        ]
    ],
    'pagination' => [
        'previous' => 'Anterior',
        'next' => 'Siguiente',
        'page' => 'Página',
    ],
    'empty_state' => [
        'title' => 'No se encontraron campeonatos',
        'subtitle' => 'Intenta modificar tus filtros o buscar algo diferente',
        'reset_button' => 'Restablecer filtros',
    ],
    'errors' => [
        'search' => 'Error de búsqueda',
        'loading' => 'Error cargando campeonatos',
        'api' => 'Error de API',
        'network' => 'Error de conexión',
    ]
];
