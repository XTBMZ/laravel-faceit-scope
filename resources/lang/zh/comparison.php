<?php
return [
    'title' => '玩家对比 - Faceit Scope',
    'hero' => [
        'title' => '玩家对比',
        'subtitle' => '对比两名 CS2 玩家的表现',
    ],
    'search' => [
        'player1' => '玩家 1',
        'player2' => '玩家 2',
        'placeholder' => 'Faceit 昵称...',
        'button' => '开始对比',
        'loading' => '分析中',
        'loading_text' => '对比玩家',
        'errors' => [
            'both_players' => '请输入两个昵称',
            'different_players' => '请输入两个不同的昵称',
        ]
    ],
    'loading' => [
        'title' => '分析中',
        'messages' => [
            'player1_data' => '获取玩家 1 数据',
            'player2_data' => '获取玩家 2 数据',
            'analyzing_stats' => '分析统计数据',
            'calculating_scores' => '计算表现分数',
            'comparing_roles' => '对比游戏角色',
            'generating_report' => '生成最终报告'
        ]
    ],
    'tabs' => [
        'overview' => '概览',
        'detailed' => '详细统计',
        'maps' => '地图'
    ],
    'winner' => [
        'analysis_complete' => '分析完成',
        'wins_analysis' => ':winner 赢得 AI 分析',
        'confidence' => '置信度：:percentage%',
        'performance_score' => '表现分数',
        'matches' => '比赛'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => '表现分数',
            'elo_impact' => 'ELO 影响',
            'combat_performance' => '战斗表现',
            'experience' => '经验',
            'advanced_stats' => '高级统计'
        ],
        'key_stats' => [
            'title' => '关键统计',
            'kd_ratio' => 'K/D 比率',
            'win_rate' => '胜率',
            'headshots' => '爆头',
            'adr' => 'ADR',
            'entry_success' => '进攻成功',
            'clutch_1v1' => '1v1 残局'
        ],
        'calculation_info' => [
            'title' => '分数如何计算？',
            'elo_impact' => [
                'title' => 'ELO 影响 (35%)',
                'description' => 'ELO 等级是最重要的因素，因为它直接反映了对阵同等实力对手的游戏水平。'
            ],
            'combat_performance' => [
                'title' => '战斗表现 (25%)',
                'description' => '结合 K/D、胜率、ADR 和 Faceit 等级来评估战斗效能。'
            ],
            'experience' => [
                'title' => '经验 (20%)',
                'description' => '已进行的比赛数量，基于累积经验的乘数。'
            ],
            'advanced_stats' => [
                'title' => '高级统计 (20%)',
                'description' => '爆头、进攻和残局能力，用于深度游戏风格分析。'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => '总体表现',
                'stats' => [
                    'total_matches' => '总比赛数',
                    'win_rate' => '胜率',
                    'wins' => '胜利',
                    'avg_kd' => '平均 K/D 比率',
                    'adr' => 'ADR (伤害/回合)'
                ]
            ],
            'combat_precision' => [
                'title' => '战斗和精准度',
                'stats' => [
                    'avg_headshots' => '平均爆头',
                    'total_headshots' => '总爆头数',
                    'total_kills' => '击杀（扩展统计）',
                    'total_damage' => '总伤害'
                ]
            ],
            'entry_fragging' => [
                'title' => '进攻突破',
                'stats' => [
                    'entry_rate' => '进攻率',
                    'entry_success' => '进攻成功率',
                    'total_entries' => '总尝试数',
                    'successful_entries' => '成功进攻'
                ]
            ],
            'clutch_situations' => [
                'title' => '残局情况',
                'stats' => [
                    '1v1_win_rate' => '1v1 胜率',
                    '1v2_win_rate' => '1v2 胜率',
                    '1v1_situations' => '1v1 情况',
                    '1v1_wins' => '1v1 胜利',
                    '1v2_situations' => '1v2 情况',
                    '1v2_wins' => '1v2 胜利'
                ]
            ],
            'utility_support' => [
                'title' => '辅助和支援',
                'stats' => [
                    'flash_success' => '闪光成功率',
                    'flashes_per_round' => '闪光弹/回合',
                    'total_flashes' => '总闪光弹',
                    'successful_flashes' => '成功闪光',
                    'enemies_flashed_per_round' => '被闪敌人/回合',
                    'total_enemies_flashed' => '总被闪敌人',
                    'utility_success' => '辅助成功率',
                    'utility_damage_per_round' => '辅助伤害/回合',
                    'total_utility_damage' => '总辅助伤害'
                ]
            ],
            'sniper_special' => [
                'title' => '狙击和特殊武器',
                'stats' => [
                    'sniper_kill_rate' => '狙击击杀率',
                    'sniper_kills_per_round' => '狙击击杀/回合',
                    'total_sniper_kills' => '总狙击击杀'
                ]
            ],
            'streaks_consistency' => [
                'title' => '连胜和稳定性',
                'stats' => [
                    'current_streak' => '当前连胜',
                    'longest_streak' => '最长连胜'
                ]
            ]
        ],
        'legend' => '绿色值表示该统计项目表现更好的玩家'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => '无共同地图',
            'description' => '两名玩家没有拥有足够数据的共同地图。'
        ],
        'dominates' => ':player 占优',
        'win_rate' => '胜率（:matches 场比赛）',
        'kd_ratio' => 'K/D 比率',
        'headshots' => '爆头',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => '地图总结',
            'maps_dominated' => '占优地图',
            'best_map' => '最佳地图',
            'none' => '无'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => '突破手',
            'description' => '专门负责进攻点位'
        ],
        'support' => [
            'name' => '支援',
            'description' => '团队辅助大师'
        ],
        'clutcher' => [
            'name' => '残局大师',
            'description' => '困难情况专家'
        ],
        'fragger' => [
            'name' => '击杀手',
            'description' => '消灭专家'
        ],
        'versatile' => [
            'name' => '全能',
            'description' => '平衡型玩家'
        ]
    ],
    'error' => [
        'title' => '错误',
        'default_message' => '对比过程中发生错误',
        'retry' => '重试',
        'player_not_found' => '未找到玩家 ":player"',
        'stats_error' => '获取统计错误：:status'
    ]
];
