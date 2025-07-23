<?php
return [
    'title' => 'Faceit Scope - Analise suas estatísticas do FACEIT',
    'hero' => [
        'subtitle' => 'Analise seu desempenho no FACEIT com algoritmos avançados e inteligência artificial. Descubra seus pontos fortes e melhore.',
        'features' => [
            'detailed_stats' => 'Estatísticas detalhadas',
            'artificial_intelligence' => 'Inteligência artificial',
            'predictive_analysis' => 'Análise preditiva',
        ]
    ],
    'search' => [
        'title' => 'Iniciar a análise',
        'subtitle' => 'Busque um jogador ou analise uma partida para descobrir insights detalhados',
        'player' => [
            'title' => 'Buscar um jogador',
            'description' => 'Analisar o desempenho de um jogador',
            'placeholder' => 'Nome do jogador FACEIT...',
            'button' => 'Buscar',
            'loading' => 'Buscando...',
        ],
        'match' => [
            'title' => 'Analisar uma partida',
            'description' => 'Previsões de IA e análise aprofundada',
            'placeholder' => 'ID da partida ou URL...',
            'button' => 'Analisar',
            'loading' => 'Analisando...',
        ],
        'errors' => [
            'empty_player' => 'Por favor, insira um nome de jogador',
            'empty_match' => 'Por favor, insira um ID de partida ou URL',
            'player_not_found' => 'O jogador ":player" não foi encontrado no FACEIT',
            'no_cs_stats' => 'O jogador ":player" nunca jogou CS2/CS:GO no FACEIT',
            'no_stats_available' => 'Nenhuma estatística disponível para ":player"',
            'match_not_found' => 'Nenhuma partida encontrada com este ID ou URL',
            'invalid_format' => 'Formato de ID ou URL de partida inválido. Exemplos válidos:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Muitas solicitações. Aguarde um momento.',
            'access_forbidden' => 'Acesso proibido. Problema com a chave da API.',
            'generic_player' => 'Erro ao buscar ":player". Verifique sua conexão.',
            'generic_match' => 'Erro ao recuperar partida. Verifique o ID ou URL.',
        ]
    ],
    'features' => [
        'title' => 'Recursos',
        'subtitle' => 'Ferramentas poderosas para analisar e melhorar seu desempenho',
        'advanced_stats' => [
            'title' => 'Estatísticas avançadas',
            'description' => 'Analise seu desempenho por mapa, acompanhe seu K/D, headshots e descubra seus melhores/piores mapas com nossos algoritmos.',
        ],
        'ai' => [
            'title' => 'Inteligência artificial',
            'description' => 'Previsões de partidas, identificação de jogadores-chave, análise de funções e recomendações personalizadas baseadas em seus dados.',
        ],
        'lobby_analysis' => [
            'title' => 'Análise de lobby',
            'description' => 'Descubra a composição de partidas, pontos fortes e obtenha previsões detalhadas sobre resultados de partidas.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Como funciona',
        'subtitle' => 'Uma abordagem científica para análise de desempenho do FACEIT',
        'steps' => [
            'data_collection' => [
                'title' => 'Coleta de dados',
                'description' => 'Usamos exclusivamente a API oficial do FACEIT para recuperar todas as suas estatísticas de forma transparente e legal.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Análise algorítmica',
                'description' => 'Nossos algoritmos analisam seus dados com normalização, ponderação e cálculos de confiança para insights precisos.',
            ],
            'personalized_insights' => [
                'title' => 'Insights personalizados',
                'description' => 'Receba análises detalhadas, previsões e recomendações para melhorar seu desempenho de jogo.',
            ]
        ]
    ]
];
