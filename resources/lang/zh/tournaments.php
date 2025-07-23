<?php
return [
    'title' => 'CS2 锦标赛 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2 锦标赛',
        'subtitle' => '发现 FACEIT 官方 CS2 锦标赛，实时关注最佳电竞赛事',
        'features' => [
            'ongoing' => '进行中的锦标赛',
            'upcoming' => '即将举行的赛事',
            'premium' => '高级锦标赛',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => '进行中',
            'upcoming' => '即将举行',
            'past' => '已结束',
            'featured' => '高级',
        ],
        'search' => [
            'placeholder' => '搜索锦标赛...',
            'button' => '搜索',
        ],
        'stats' => [
            'ongoing' => '进行中',
            'upcoming' => '即将举行',
            'prize_pools' => '奖金池',
            'participants' => '参与者',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => '高级',
            'ongoing' => '进行中',
            'upcoming' => '即将举行',
            'finished' => '已结束',
            'cancelled' => '已取消',
        ],
        'info' => [
            'participants' => '参与者',
            'prize_pool' => '奖金池',
            'registrations' => '注册',
            'organizer' => '组织者',
            'status' => '状态',
            'region' => '地区',
            'level' => '等级',
            'slots' => '名额',
        ],
        'actions' => [
            'details' => '详情',
            'view_faceit' => '在 FACEIT 查看',
            'view_matches' => '查看比赛',
            'results' => '结果',
            'close' => '关闭',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => '加载详情中...',
            'subtitle' => '获取锦标赛信息',
        ],
        'error' => [
            'title' => '加载错误',
            'subtitle' => '无法加载锦标赛详情',
        ],
        'sections' => [
            'description' => '描述',
            'information' => '信息',
            'matches' => '锦标赛比赛',
            'results' => '锦标赛结果',
            'default_description' => '此锦标赛是 FACEIT 组织的官方 CS2 竞赛的一部分。',
        ],
        'matches' => [
            'loading' => '加载比赛中...',
            'no_matches' => '此锦标赛无可用比赛',
            'error' => '加载比赛错误',
            'status' => [
                'finished' => '已结束',
                'ongoing' => '进行中',
                'upcoming' => '即将举行',
            ]
        ],
        'results' => [
            'loading' => '加载结果中...',
            'no_results' => '此锦标赛无可用结果',
            'error' => '加载结果错误',
            'position' => '位置',
        ]
    ],
    'pagination' => [
        'previous' => '上一页',
        'next' => '下一页',
        'page' => '页面',
    ],
    'empty_state' => [
        'title' => '未找到锦标赛',
        'subtitle' => '尝试修改您的过滤器或搜索其他内容',
        'reset_button' => '重置过滤器',
    ],
    'errors' => [
        'search' => '搜索错误',
        'loading' => '加载锦标赛错误',
        'api' => 'API 错误',
        'network' => '连接错误',
    ]
];
