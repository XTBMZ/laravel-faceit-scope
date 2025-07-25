<?php
return [
    'title' => 'Meus Amigos do FACEIT',
    'subtitle' => 'Descubra o desempenho do seu círculo de jogos',
    'load_more' => 'Ver mais :count',
    'stats' => [
        'total' => 'Total',
        'active_7d' => 'Ativos (7d)',
        'average_elo' => 'ELO Médio',
        'best' => 'Melhor',
    ],
    'search_placeholder' => 'Buscar um amigo...',
    'activity_filter' => [
        'all' => 'Toda atividade',
        'recent' => 'Recente (7d)',
        'month' => 'Este mês',
        'inactive' => 'Inativo (30d+)',
    ],
    'sort_by' => [
        'elo' => 'ELO',
        'activity' => 'Atividade',
        'name' => 'Nome',
        'level' => 'Nível',
    ],
    'loading' => [
        'title' => 'Carregando amigos...',
        'connecting' => 'Conectando...',
        'fetching_friends' => 'Obtendo lista de amigos...',
        'loading_all' => 'Carregando todos os amigos...',
        'finalizing' => 'Finalizando...',
    ],
    'empty' => [
        'title' => 'Nenhum amigo encontrado',
        'description' => 'Você ainda não tem amigos no FACEIT',
        'action' => 'Ir para o FACEIT',
    ],
    'error' => [
        'title' => 'Erro de carregamento',
        'not_authenticated' => 'Não autenticado',
        'missing_data' => 'Dados do usuário ausentes',
        'load_failed' => 'Não é possível carregar seus amigos. Verifique sua conexão.',
        'server_error' => 'Erro do servidor. Tente novamente mais tarde.',
    ],
    'modal' => [
        'title' => 'Detalhes do amigo',
        'last_activity' => 'Última atividade',
        'elo_faceit' => 'ELO do FACEIT',
        'view_faceit' => 'Ver no FACEIT',
        'view_stats' => 'Ver estatísticas',
    ],
    'activity' => [
        'today' => 'Hoje',
        'yesterday' => 'Ontem',
        'days_ago' => 'há :count dias',
        'weeks_ago' => 'há :count semana',
        'weeks_ago_plural' => 'há :count semanas',
        'months_ago' => 'há :count meses',
        'no_recent' => 'Nenhuma atividade recente',
    ],
    'count' => ':count amigos',
    'filtered_count' => '(:count mostrados)',
    'load_more' => 'Mostrar mais :count',
    'success_rate' => ':percentage% sucesso',
    'friends_loaded' => ':loaded amigos carregados de :total',
];
