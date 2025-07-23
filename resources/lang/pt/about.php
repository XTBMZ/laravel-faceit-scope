<?php
return [
    'title' => 'Sobre - Faceit Scope',
    'hero' => [
        'title' => 'Sobre',
        'subtitle' => 'Faceit Scope analisa seu desempenho no FACEIT usando algoritmos avançados e inteligência artificial. Um projeto solo desenvolvido com paixão.',
    ],
    'project' => [
        'title' => 'O projeto',
        'description_1' => 'permite análise aprofundada de desempenho.',
        'description_2' => 'Totalmente desenvolvido por',
        'description_3' => 'este projeto usa exclusivamente a API oficial do FACEIT para recuperar todos os dados de forma transparente e legal.',
        'description_4' => 'Tudo vem diretamente dos servidores do FACEIT e é analisado por nossos algoritmos proprietários.',
        'stats' => [
            'developer' => 'Desenvolvedor',
            'api' => 'API do FACEIT',
        ],
    ],
    'how_it_works' => [
        'title' => 'Como funciona',
        'subtitle' => 'Algoritmos sofisticados analisam seus dados do FACEIT para dar insights precisos',
        'pis' => [
            'title' => 'Pontuação de Impacto do Jogador (PIS)',
            'combat' => [
                'title' => 'Combate (35%)',
                'description' => 'K/D, ADR e taxa de headshots com normalização logarítmica',
            ],
            'game_sense' => [
                'title' => 'Game Sense (25%)',
                'description' => 'Habilidades de entrada, clutch e sniper com combinações avançadas',
            ],
            'utility' => [
                'title' => 'Utilidade (15%)',
                'description' => 'Suporte e uso de utilidades com eficiência ponderada',
            ],
            'consistency' => [
                'title' => 'Consistência + Exp (25%)',
                'description' => 'Taxa de vitórias, sequências e confiabilidade dos dados',
            ],
            'level_coefficient' => [
                'title' => 'Coeficiente de nível crucial:',
                'description' => 'Um Level 10 com 1.0 K/D será avaliado mais alto que um Level 2 com 1.5 K/D porque joga contra oponentes mais fortes.',
            ],
        ],
        'roles' => [
            'title' => 'Atribuição inteligente de funções',
            'calculations_title' => 'Cálculos de pontuação de funções',
            'priority_title' => 'Prioridade de atribuição',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Critérios específicos: Taxa de Entrada > 25% E Sucesso de Entrada > 55%',
            ],
            'support' => [
                'title' => 'Suporte',
                'criteria' => 'Critérios específicos: Flashes > 0.4/round E Sucesso de Flash > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Critérios específicos: Taxa de Sniper > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (se sniper > 15%)',
                'entry' => 'Entrada (se entrada > 25% + sucesso > 55%)',
                'support' => 'Suporte (se flashes > 0.4 + sucesso > 50%)',
                'clutcher' => 'Clutcher (se 1v1 > 40%)',
                'fragger' => 'Fragger (se K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (padrão se não há critérios)',
            ],
        ],
        'maps' => [
            'title' => 'Algoritmo de análise de mapas',
            'normalization' => [
                'title' => 'Normalização logarítmica',
            ],
            'weighting' => [
                'title' => 'Ponderação avançada',
                'win_rate' => 'Taxa de Vitórias:',
                'consistency' => 'Consistência:',
            ],
            'reliability' => [
                'title' => 'Fator de confiabilidade',
            ],
        ],
        'predictions' => [
            'title' => 'Previsões de partidas',
            'team_strength' => [
                'title' => 'Força da equipe',
                'average_score' => [
                    'title' => 'Pontuação média ponderada',
                    'description' => 'Média de 5 pontuações PIS + bônus de equilíbrio de funções',
                ],
                'role_balance' => [
                    'title' => 'Equilíbrio de funções',
                    'description' => 'Uma equipe com Entry + Suporte + AWPer + Clutcher + Fragger terá um bônus significativo versus 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Cálculos de probabilidade',
                'match_winner' => [
                    'title' => 'Vencedor da partida',
                    'description' => 'Quanto maior a diferença de força, mais confiante a previsão',
                ],
                'predicted_mvp' => [
                    'title' => 'MVP previsto',
                    'description' => 'O jogador com a',
                    'description_end' => 'entre os 10 participantes',
                    'highest_score' => 'maior pontuação PIS',
                ],
                'confidence' => [
                    'title' => 'Nível de confiança',
                    'description' => 'Baseado na diferença de força: Muito Alto (>3), Alto (>2), Moderado (>1), Baixo (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contato',
        'subtitle' => 'Um projeto solo desenvolvido com paixão. Sinta-se à vontade para me contatar para feedback ou sugestões.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope não é afiliado ao FACEIT Ltd. Este projeto usa a API pública do FACEIT em conformidade com seus termos de serviço. Algoritmos de previsão são baseados em análise estatística e não garantem resultados de partidas.',
    ],
];
