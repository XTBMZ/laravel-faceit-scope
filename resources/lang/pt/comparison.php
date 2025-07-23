<?php
return [
    'title' => 'Comparação de Jogadores - Faceit Scope',
    'hero' => [
        'title' => 'Comparação de Jogadores',
        'subtitle' => 'Compare o desempenho de dois jogadores de CS2',
    ],
    'search' => [
        'player1' => 'Jogador 1',
        'player2' => 'Jogador 2',
        'placeholder' => 'Nickname do Faceit...',
        'button' => 'Iniciar comparação',
        'loading' => 'Análise em progresso',
        'loading_text' => 'Comparação de jogadores',
        'errors' => [
            'both_players' => 'Por favor, insira ambos os nicknames',
            'different_players' => 'Por favor, insira dois nicknames diferentes',
        ]
    ],
    'loading' => [
        'title' => 'Análise em progresso',
        'messages' => [
            'player1_data' => 'Recuperando dados do jogador 1',
            'player2_data' => 'Recuperando dados do jogador 2',
            'analyzing_stats' => 'Analisando estatísticas',
            'calculating_scores' => 'Calculando pontuações de desempenho',
            'comparing_roles' => 'Comparando funções de jogo',
            'generating_report' => 'Gerando relatório final'
        ]
    ],
    'tabs' => [
        'overview' => 'Visão Geral',
        'detailed' => 'Estatísticas detalhadas',
        'maps' => 'Mapas'
    ],
    'winner' => [
        'analysis_complete' => 'Análise Completa Finalizada',
        'wins_analysis' => ':winner ganha a análise de IA',
        'confidence' => 'Confiança: :percentage%',
        'performance_score' => 'Pontuação de Desempenho',
        'matches' => 'Partidas'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Pontuações de desempenho',
            'elo_impact' => 'Impacto ELO',
            'combat_performance' => 'Desempenho de Combate',
            'experience' => 'Experiência',
            'advanced_stats' => 'Estatísticas Avançadas'
        ],
        'key_stats' => [
            'title' => 'Estatísticas principais',
            'kd_ratio' => 'Ratio K/D',
            'win_rate' => 'Taxa de vitórias',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Sucesso de Entrada',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Como as pontuações são calculadas?',
            'elo_impact' => [
                'title' => 'Impacto ELO (35%)',
                'description' => 'O nível ELO é o fator mais importante pois reflete diretamente o nível de jogo contra oponentes de força igual.'
            ],
            'combat_performance' => [
                'title' => 'Desempenho de Combate (25%)',
                'description' => 'Combina K/D, taxa de vitórias, ADR e nível do Faceit para avaliar efetividade em combate.'
            ],
            'experience' => [
                'title' => 'Experiência (20%)',
                'description' => 'Número de partidas jogadas com multiplicador baseado na experiência acumulada.'
            ],
            'advanced_stats' => [
                'title' => 'Estatísticas Avançadas (20%)',
                'description' => 'Headshots, entry fragging e habilidades de clutch para análise aprofundada do estilo de jogo.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Desempenho geral',
                'stats' => [
                    'total_matches' => 'Partidas totais',
                    'win_rate' => 'Taxa de vitórias',
                    'wins' => 'Vitórias',
                    'avg_kd' => 'Ratio K/D médio',
                    'adr' => 'ADR (dano/round)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Combate e precisão',
                'stats' => [
                    'avg_headshots' => 'Headshots médios',
                    'total_headshots' => 'Headshots totais',
                    'total_kills' => 'Kills (estatísticas estendidas)',
                    'total_damage' => 'Dano total'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Taxa de entrada',
                    'entry_success' => 'Taxa de sucesso de entrada',
                    'total_entries' => 'Tentativas totais',
                    'successful_entries' => 'Entradas bem-sucedidas'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Situações de clutch',
                'stats' => [
                    '1v1_win_rate' => 'Taxa de vitórias 1v1',
                    '1v2_win_rate' => 'Taxa de vitórias 1v2',
                    '1v1_situations' => 'Situações 1v1',
                    '1v1_wins' => 'Vitórias 1v1',
                    '1v2_situations' => 'Situações 1v2',
                    '1v2_wins' => 'Vitórias 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utilidade e suporte',
                'stats' => [
                    'flash_success' => 'Taxa de sucesso de flash',
                    'flashes_per_round' => 'Flashes por round',
                    'total_flashes' => 'Flashes totais',
                    'successful_flashes' => 'Flashes bem-sucedidos',
                    'enemies_flashed_per_round' => 'Inimigos flashados/round',
                    'total_enemies_flashed' => 'Inimigos flashados totais',
                    'utility_success' => 'Taxa de sucesso de utilidade',
                    'utility_damage_per_round' => 'Dano de utilidade/round',
                    'total_utility_damage' => 'Dano de utilidade total'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper e armas especiais',
                'stats' => [
                    'sniper_kill_rate' => 'Taxa de kills de sniper',
                    'sniper_kills_per_round' => 'Kills de sniper/round',
                    'total_sniper_kills' => 'Kills de sniper totais'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Sequências e consistência',
                'stats' => [
                    'current_streak' => 'Sequência atual',
                    'longest_streak' => 'Maior sequência'
                ]
            ]
        ],
        'legend' => 'Valores em verde indicam o jogador com melhor desempenho para cada estatística'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Nenhum mapa em comum',
            'description' => 'Ambos os jogadores não têm mapas em comum com dados suficientes.'
        ],
        'dominates' => ':player domina',
        'win_rate' => 'Taxa de Vitórias (:matches partidas)',
        'kd_ratio' => 'Ratio K/D',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Resumo dos mapas',
            'maps_dominated' => 'Mapas dominados',
            'best_map' => 'Melhor mapa',
            'none' => 'Nenhum'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Especializado em entradas de site'
        ],
        'support' => [
            'name' => 'Suporte',
            'description' => 'Mestre de utilidades da equipe'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Especialista em situações difíceis'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Especialista em eliminações'
        ],
        'versatile' => [
            'name' => 'Versátil',
            'description' => 'Jogador equilibrado'
        ]
    ],
    'error' => [
        'title' => 'Erro',
        'default_message' => 'Ocorreu um erro durante a comparação',
        'retry' => 'Tentar novamente',
        'player_not_found' => 'Jogador ":player" não encontrado',
        'stats_error' => 'Erro ao recuperar estatísticas: :status'
    ]
];
