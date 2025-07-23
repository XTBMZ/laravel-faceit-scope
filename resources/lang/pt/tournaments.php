<?php
return [
    'title' => 'Campeonatos de CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Campeonatos de CS2',
        'subtitle' => 'Descubra os campeonatos oficiais de CS2 do FACEIT e acompanhe os melhores eventos de eSports em tempo real',
        'features' => [
            'ongoing' => 'Campeonatos em andamento',
            'upcoming' => 'Próximos eventos',
            'premium' => 'Campeonatos premium',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Em andamento',
            'upcoming' => 'Próximos',
            'past' => 'Finalizados',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Buscar um campeonato...',
            'button' => 'Buscar',
        ],
        'stats' => [
            'ongoing' => 'Em andamento',
            'upcoming' => 'Próximos',
            'prize_pools' => 'Prêmios em dinheiro',
            'participants' => 'Participantes',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'EM ANDAMENTO',
            'upcoming' => 'PRÓXIMO',
            'finished' => 'FINALIZADO',
            'cancelled' => 'CANCELADO',
        ],
        'info' => [
            'participants' => 'Participantes',
            'prize_pool' => 'Prêmio em dinheiro',
            'registrations' => 'Inscrições',
            'organizer' => 'Organizador',
            'status' => 'Status',
            'region' => 'Região',
            'level' => 'Nível',
            'slots' => 'Vagas',
        ],
        'actions' => [
            'details' => 'Detalhes',
            'view_faceit' => 'Ver no FACEIT',
            'view_matches' => 'Ver partidas',
            'results' => 'Resultados',
            'close' => 'Fechar',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Carregando detalhes...',
            'subtitle' => 'Recuperando informações do campeonato',
        ],
        'error' => [
            'title' => 'Erro de carregamento',
            'subtitle' => 'Não foi possível carregar os detalhes do campeonato',
        ],
        'sections' => [
            'description' => 'Descrição',
            'information' => 'Informações',
            'matches' => 'Partidas do campeonato',
            'results' => 'Resultados do campeonato',
            'default_description' => 'Este campeonato faz parte das competições oficiais de CS2 organizadas no FACEIT.',
        ],
        'matches' => [
            'loading' => 'Carregando partidas...',
            'no_matches' => 'Nenhuma partida disponível para este campeonato',
            'error' => 'Erro ao carregar partidas',
            'status' => [
                'finished' => 'Finalizada',
                'ongoing' => 'Em andamento',
                'upcoming' => 'Próxima',
            ]
        ],
        'results' => [
            'loading' => 'Carregando resultados...',
            'no_results' => 'Nenhum resultado disponível para este campeonato',
            'error' => 'Erro ao carregar resultados',
            'position' => 'Posição',
        ]
    ],
    'pagination' => [
        'previous' => 'Anterior',
        'next' => 'Próximo',
        'page' => 'Página',
    ],
    'empty_state' => [
        'title' => 'Nenhum campeonato encontrado',
        'subtitle' => 'Tente modificar seus filtros ou buscar algo diferente',
        'reset_button' => 'Redefinir filtros',
    ],
    'errors' => [
        'search' => 'Erro de busca',
        'loading' => 'Erro ao carregar campeonatos',
        'api' => 'Erro de API',
        'network' => 'Erro de conexão',
    ]
];
