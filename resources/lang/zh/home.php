<?php
return [
    'title' => 'Faceit Scope - 分析您的 FACEIT 统计数据',
    'hero' => [
        'subtitle' => '使用先进算法和人工智能分析您在 FACEIT 上的表现。发现您的优势并提升技能。',
        'features' => [
            'detailed_stats' => '详细统计',
            'artificial_intelligence' => '人工智能',
            'predictive_analysis' => '预测分析',
        ]
    ],
    'search' => [
        'title' => '开始分析',
        'subtitle' => '搜索玩家或分析比赛以发现详细洞察',
        'player' => [
            'title' => '搜索玩家',
            'description' => '分析玩家表现',
            'placeholder' => 'FACEIT 玩家姓名...',
            'button' => '搜索',
            'loading' => '搜索中...',
        ],
        'match' => [
            'title' => '分析比赛',
            'description' => 'AI 预测和深度分析',
            'placeholder' => '比赛 ID 或 URL...',
            'button' => '分析',
            'loading' => '分析中...',
        ],
        'errors' => [
            'empty_player' => '请输入玩家姓名',
            'empty_match' => '请输入比赛 ID 或 URL',
            'player_not_found' => '在 FACEIT 上未找到玩家 ":player"',
            'no_cs_stats' => '玩家 ":player" 从未在 FACEIT 上玩过 CS2/CS:GO',
            'no_stats_available' => '":player" 无统计数据',
            'match_not_found' => '未找到此 ID 或 URL 的比赛',
            'invalid_format' => '比赛 ID 或 URL 格式无效。有效示例：\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => '请求过多。请稍等。',
            'access_forbidden' => '访问被禁止。API 密钥问题。',
            'generic_player' => '搜索 ":player" 错误。请检查连接。',
            'generic_match' => '获取比赛错误。请检查 ID 或 URL。',
        ]
    ],
    'features' => [
        'title' => '功能',
        'subtitle' => '强大工具来分析和提升您的表现',
        'advanced_stats' => [
            'title' => '高级统计',
            'description' => '按地图分析您的表现，跟踪您的 K/D、爆头并通过我们的算法发现您的最佳/最差地图。',
        ],
        'ai' => [
            'title' => '人工智能',
            'description' => '比赛预测、关键玩家识别、角色分析和基于您数据的个性化建议。',
        ],
        'lobby_analysis' => [
            'title' => '大厅分析',
            'description' => '发现比赛组成、优势并获得详细的比赛结果预测。',
        ]
    ],
    'how_it_works' => [
        'title' => '工作原理',
        'subtitle' => 'FACEIT 表现分析的科学方法',
        'steps' => [
            'data_collection' => [
                'title' => '数据收集',
                'description' => '我们仅使用官方 FACEIT API 以透明合法的方式获取您的所有统计数据。',
            ],
            'algorithmic_analysis' => [
                'title' => '算法分析',
                'description' => '我们的算法通过标准化、加权和置信度计算分析您的数据以获得精确洞察。',
            ],
            'personalized_insights' => [
                'title' => '个性化洞察',
                'description' => '获得详细分析、预测和建议以提升您的游戏表现。',
            ]
        ]
    ]
];
