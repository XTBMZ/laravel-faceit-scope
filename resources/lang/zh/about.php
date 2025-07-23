<?php
return [
    'title' => '关于我们 - Faceit Scope',
    'hero' => [
        'title' => '关于我们',
        'subtitle' => 'Faceit Scope 使用先进算法和人工智能分析您在 FACEIT 上的表现。这是一个充满热情开发的项目。',
    ],
    'project' => [
        'title' => '项目介绍',
        'description_1' => '允许深入分析表现。',
        'description_2' => '完全由',
        'description_3' => '开发，该项目仅使用 FACEIT 官方 API 以透明合法的方式获取所有数据。',
        'description_4' => '一切都直接来自 FACEIT 服务器，并由我们的专有算法进行分析。',
        'stats' => [
            'developer' => '开发者',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => '工作原理',
        'subtitle' => '先进算法分析您的 FACEIT 数据，为您提供精确的洞察',
        'pis' => [
            'title' => '玩家影响力评分 (PIS)',
            'combat' => [
                'title' => '战斗 (35%)',
                'description' => 'K/D、ADR 和爆头率，采用对数标准化',
            ],
            'game_sense' => [
                'title' => '游戏意识 (25%)',
                'description' => '进攻能力、残局和狙击能力，采用高级组合',
            ],
            'utility' => [
                'title' => '辅助 (15%)',
                'description' => '支援和辅助工具使用，采用加权效率',
            ],
            'consistency' => [
                'title' => '稳定性 + 经验 (25%)',
                'description' => '胜率、连胜和数据可靠性',
            ],
            'level_coefficient' => [
                'title' => '关键等级系数：',
                'description' => '一个拥有 1.0 K/D 的 10 级玩家比拥有 1.5 K/D 的 2 级玩家评分更高，因为他对阵的对手更强。',
            ],
        ],
        'roles' => [
            'title' => '智能角色分配',
            'calculations_title' => '角色评分计算',
            'priority_title' => '分配优先级',
            'entry_fragger' => [
                'title' => '突破手',
                'criteria' => '特定标准：进攻率 > 25% 且 进攻成功率 > 55%',
            ],
            'support' => [
                'title' => '支援',
                'criteria' => '特定标准：闪光弹 > 0.4/回合 且 闪光成功率 > 50%',
            ],
            'awper' => [
                'title' => 'AWP手',
                'criteria' => '特定标准：狙击率 > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWP手 (如果狙击 > 15%)',
                'entry' => '突破手 (如果进攻 > 25% + 成功率 > 55%)',
                'support' => '支援 (如果闪光 > 0.4 + 成功率 > 50%)',
                'clutcher' => '残局大师 (如果 1v1 > 40%)',
                'fragger' => '击杀手 (如果 K/D > 1.3 + ADR > 85)',
                'lurker' => '潜伏者 (默认，如果没有其他标准)',
            ],
        ],
        'maps' => [
            'title' => '地图分析算法',
            'normalization' => [
                'title' => '对数标准化',
            ],
            'weighting' => [
                'title' => '高级加权',
                'win_rate' => '胜率：',
                'consistency' => '稳定性：',
            ],
            'reliability' => [
                'title' => '可靠性因子',
            ],
        ],
        'predictions' => [
            'title' => '比赛预测',
            'team_strength' => [
                'title' => '队伍实力',
                'average_score' => [
                    'title' => '加权平均分',
                    'description' => '5 个 PIS 分数的平均值 + 角色平衡奖励',
                ],
                'role_balance' => [
                    'title' => '角色平衡',
                    'description' => '拥有突破手 + 支援 + AWP手 + 残局大师 + 击杀手的队伍将比 5 个击杀手组成的队伍获得显著奖励。',
                ],
            ],
            'probability' => [
                'title' => '概率计算',
                'match_winner' => [
                    'title' => '比赛获胜者',
                    'description' => '实力差距越大，预测越准确',
                ],
                'predicted_mvp' => [
                    'title' => '预测 MVP',
                    'description' => '拥有',
                    'description_end' => '的玩家将成为 10 名参与者中的预测 MVP',
                    'highest_score' => '最高 PIS 分数',
                ],
                'confidence' => [
                    'title' => '置信度',
                    'description' => '基于实力差距：非常高 (>3)，高 (>2)，中等 (>1)，低 (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => '联系方式',
        'subtitle' => '这是一个充满热情开发的项目。欢迎联系我提供反馈或建议。',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope 与 FACEIT Ltd. 无关联。该项目使用 FACEIT 公共 API，符合其服务条款。预测算法基于统计分析，不保证比赛结果。',
    ],
];
