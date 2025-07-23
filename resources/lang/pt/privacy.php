<?php
return [
    'title' => 'Política de Privacidade - Faceit Scope',
    'header' => [
        'title' => 'Política de Privacidade',
        'subtitle' => 'Extensão Faceit Scope',
        'last_updated' => 'Última atualização: 23 de julho de 2025',
    ],
    'introduction' => [
        'title' => '1. Introdução',
        'content' => 'Faceit Scope é uma extensão de navegador que analisa partidas de CS2 do FACEIT para exibir estatísticas e previsões. Respeitamos sua privacidade e estamos comprometidos em proteger seus dados pessoais.',
    ],
    'data_collected' => [
        'title' => '2. Dados coletados',
        'temporary_data' => [
            'title' => '2.1 Dados processados temporariamente (não armazenados)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Nomes de usuário públicos do FACEIT:',
                    'description' => 'Pseudônimos de jogos já exibidos publicamente no FACEIT, lidos temporariamente para análise',
                ],
                'public_stats' => [
                    'title' => 'Estatísticas públicas de jogo:',
                    'description' => 'K/D, taxa de vitórias, mapas jogados (via API pública do FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'IDs de partida:',
                    'description' => 'Extraídos da URL para identificar partidas a analisar',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Dados armazenados localmente (apenas cache temporário)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Resultados de análise:',
                    'description' => 'Armazenados no máximo 5 minutos no seu dispositivo para evitar chamadas repetitivas à API',
                ],
                'user_preferences' => [
                    'title' => 'Preferências do usuário:',
                    'description' => 'Configurações da extensão (notificações habilitadas/desabilitadas)',
                ],
            ],
        ],
        'important_note' => 'Importante: Nenhum dado de identificação pessoal é coletado ou retido. Todos os dados processados já são públicos no FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Uso de dados',
        'description' => 'Os dados coletados são usados exclusivamente para:',
        'items' => [
            'display_stats' => 'Exibir estatísticas de jogadores na interface do FACEIT',
            'predictions' => 'Calcular previsões da equipe vencedora',
            'map_recommendations' => 'Recomendar melhores/piores mapas por equipe',
            'performance' => 'Melhorar o desempenho através do cache temporário',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Compartilhamento de dados',
        'no_third_party' => [
            'title' => '4.1 Nenhum compartilhamento com terceiros',
            'items' => [
                'no_selling' => 'Não vendemos nenhum dado para terceiros',
                'no_transfer' => 'Não transferimos nenhum dado pessoal',
                'local_analysis' => 'Todas as análises são realizadas localmente no seu navegador',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API do FACEIT',
            'items' => [
                'public_api' => 'A extensão usa apenas a API pública oficial do FACEIT',
                'no_private_data' => 'Nenhum dado privado ou sensível é coletado',
                'public_stats' => 'Todas as estatísticas utilizadas são de acesso público',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Segurança e retenção',
        'local_storage' => [
            'title' => '5.1 Apenas armazenamento local',
            'items' => [
                'local_only' => 'Todos os dados são armazenados localmente no seu dispositivo',
                'no_server_transmission' => 'Nenhum dado é transmitido para nossos servidores',
                'auto_delete' => 'O cache é excluído automaticamente após 5 minutos',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Acesso limitado',
            'items' => [
                'faceit_only' => 'A extensão acessa apenas páginas do FACEIT que você visita',
                'no_other_access' => 'Nenhum acesso a outros sites ou dados pessoais',
                'no_tracking' => 'Nenhum rastreamento da sua navegação',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Seus direitos',
        'data_control' => [
            'title' => '6.1 Controle de dados',
            'items' => [
                'clear_cache' => 'Você pode limpar o cache a qualquer momento via popup da extensão',
                'uninstall' => 'Você pode desinstalar a extensão para remover todos os dados',
                'disable_notifications' => 'Você pode desabilitar notificações nas configurações',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Dados públicos',
            'items' => [
                'already_public' => 'Todos os dados analisados já são públicos no FACEIT',
                'no_private_info' => 'A extensão não revela nenhuma informação privada',
                'no_personal_data' => 'Nenhum dado de identificação pessoal é coletado',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies e tecnologias de rastreamento',
        'description' => 'A extensão Faceit Scope:',
        'does_not_use' => [
            'title' => 'Não utiliza:',
            'items' => [
                'no_cookies' => 'Nenhum cookie',
                'no_ad_tracking' => 'Nenhum rastreamento publicitário',
                'no_behavioral_analysis' => 'Nenhuma análise comportamental',
            ],
        ],
        'uses_only' => [
            'title' => 'Utiliza apenas:',
            'items' => [
                'local_storage' => 'Armazenamento local do navegador',
                'temp_cache' => 'Cache temporário (máximo 5 minutos)',
                'public_api' => 'API pública do FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Atualizações desta política',
        'content' => 'Podemos atualizar esta política de privacidade. Mudanças serão publicadas nesta página com uma nova data e notificadas via atualização da extensão se necessário.',
    ],
    'contact' => [
        'title' => '9. Contato',
        'description' => 'Para qualquer pergunta sobre esta política de privacidade:',
        'website' => 'Site:',
        'email' => 'E-mail:',
    ],
    'compliance' => [
        'title' => '10. Conformidade regulatória',
        'description' => 'Esta extensão está em conformidade com:',
        'items' => [
            'gdpr' => 'O Regulamento Geral sobre a Proteção de Dados (GDPR)',
            'chrome_store' => 'Políticas da Chrome Web Store',
            'faceit_terms' => 'Termos de uso da API do FACEIT',
        ],
    ],
];
