#!/bin/bash

# Script de création des fichiers de traduction chinoise
# Traductions complètes pour le marché chinois

set -e  # Arrêter le script en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Configuration
LANG_DIR="resources/lang"
TARGET_LANG="zh"
TARGET_DIR="${LANG_DIR}/${TARGET_LANG}"

echo -e "${BLUE}🇨🇳 Script de traduction chinoise - Complet${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""

# Vérifier que le répertoire cible existe ou le créer
if [ ! -d "$TARGET_DIR" ]; then
    echo -e "${YELLOW}📁 Création du répertoire '$TARGET_DIR'${NC}"
    mkdir -p "$TARGET_DIR"
else
    echo -e "${GREEN}📁 Répertoire trouvé: $TARGET_DIR${NC}"
fi

echo -e "${YELLOW}🔄 Début de la traduction des fichiers...${NC}"
echo ""

# Liste des fichiers à traiter
FILES_TO_TRANSLATE=(
    "about.php"
    "advanced.php"
    "auth.php"
    "common.php"
    "comparison.php"
    "contact.php"
    "errors.php"
    "footer.php"
    "friends.php"
    "home.php"
    "language.php"
    "leaderboards.php"
    "messages.php"
    "navigation.php"
    "privacy.php"
    "tournaments.php"
)

# ===============================
# ABOUT.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: about.php${NC}"
cat > "${TARGET_DIR}/about.php" << 'EOF'
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
EOF

# ===============================
# ADVANCED.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: advanced.php${NC}"
cat > "${TARGET_DIR}/advanced.php" << 'EOF'
<?php
return [
    'title' => '高级统计 - Faceit Scope',
    'loading' => [
        'title' => '分析中',
        'default_text' => '获取数据',
        'messages' => [
            'player_data' => '获取玩家数据',
            'analyzing_stats' => '分析统计数据',
            'calculating_performance' => '计算表现',
            'generating_charts' => '生成图表',
            'finalizing' => '完成中...',
        ],
    ],
    'player' => [
        'current_elo' => '当前 ELO',
        'rank' => '排名',
        'level' => '等级 :level',
        'faceit_button' => 'FACEIT',
        'compare_button' => '对比',
    ],
    'sections' => [
        'overview' => '概览',
        'combat_performance' => '战斗表现',
        'graphical_analysis' => '图表分析',
        'map_analysis' => '地图分析',
        'achievements' => '成就',
        'recent_form' => '近期状态',
        'quick_actions' => '快速操作',
    ],
    'stats' => [
        'matches' => '比赛',
        'win_rate' => '胜率',
        'kd_ratio' => 'K/D 比率',
        'headshots' => '爆头',
        'kr_ratio' => 'K/R 比率',
        'entry_rate' => '进攻率',
        'clutch_master' => '残局大师',
        'total_clutches' => '总残局数',
        'entry_fragger' => '突破手',
        'success_rate' => '成功率',
        'support_master' => '支援大师',
        'flash_success' => '闪光成功',
        'performance_radar' => '表现雷达',
        'map_distribution' => '地图分布',
    ],
    'detailed_stats' => [
        '1v1_win_rate' => '1v1 胜率',
        '1v2_win_rate' => '1v2 胜率',
        'entry_rate' => '进攻率',
        'total_entries' => '总进攻数',
        'successful_entries' => '成功进攻',
        'flashes_per_round' => '闪光弹/回合',
        'utility_success' => '辅助成功率',
        'total_flash_assists' => '总闪光助攻',
    ],
    'achievements' => [
        'ace' => 'Ace (5杀)',
        'quadro' => '四杀 (4K)',
        'triple' => '三杀 (3K)',
        'current_streak' => '当前连胜',
        'longest_streak' => '最长连胜',
    ],
    'recent_results' => [
        'title' => '近期结果',
        'last_matches' => '最近 :count 场比赛',
        'no_results' => '无近期结果',
        'victory' => '胜利',
        'defeat' => '失败',
        'match_number' => '比赛 :number',
    ],
    'actions' => [
        'compare_player' => '对比此玩家',
        'download_report' => '下载报告',
        'view_progression' => '查看进度',
    ],
    'map_modal' => [
        'matches_played' => '已进行 :matches 场比赛',
        'victories' => ':winrate% 胜利',
        'combat' => '战斗',
        'multi_kills' => '多杀',
        'entry_performance' => '进攻表现',
        'clutch_performance' => '残局表现',
        'utility_performance' => '辅助表现',
        'sniper_performance' => '狙击表现',
        'close' => '关闭',
        'share' => '分享',
        'view_details' => '查看详情',
        'total_kills' => '总击杀',
        'total_deaths' => '总死亡',
        'total_assists' => '总助攻',
        'kills_per_round' => '击杀/回合',
        'deaths_per_round' => '死亡/回合',
        'opening_kill_ratio' => '首杀比率',
        'aces' => 'Ace (5杀)',
        'quadros' => '四杀 (4K)',
        'triples' => '三杀 (3K)',
        'avg_aces_per_match' => '平均 Ace/比赛',
        'avg_4k_per_match' => '平均 4K/比赛',
        'avg_3k_per_match' => '平均 3K/比赛',
        'total_entries' => '总进攻数',
        'success_rate' => '成功率',
        'successes_attempts' => ':wins 成功 / :total 尝试',
        'entry_wins_per_match' => '进攻胜利/比赛',
        'entry_attempts' => '进攻尝试',
        'enemies_flashed' => '敌人被闪',
        'flash_per_round' => '闪光弹/回合',
        '1v1_rate' => '1v1 比率',
        '1v2_rate' => '1v2 比率',
        'victories' => ':wins/:total 胜利',
        '1v3_wins' => '1v3 胜利',
        '1v4_wins' => '1v4 胜利',
        '1v5_wins' => '1v5 胜利',
        'total_clutches' => '总残局数',
        'flash_success' => '闪光成功',
        'successful_flashes' => ':successes/:total 成功',
        'flashes_per_round' => '闪光弹/回合',
        'utility_damage' => '辅助伤害',
        'utility_success' => '辅助成功率',
        'total_flashes' => '总闪光弹',
        'sniper_kills' => '狙击击杀',
        'sniper_k_per_round' => '狙击击杀/回合',
        'avg_sniper_k_per_match' => '平均狙击击杀/比赛',
        'sniper_kill_rate' => '狙击击杀率',
        'total_damage' => '总伤害',
        'utility_usage_per_round' => '辅助使用/回合',
        'awp_expert' => 'AWP 专家！',
    ],
    'errors' => [
        'no_player' => '未指定玩家',
        'player_not_found' => '未找到玩家',
        'loading_error' => '加载统计数据错误',
        'no_export_data' => '无数据可导出',
        'back_home' => '返回首页',
    ],
    'notifications' => [
        'report_downloaded' => '报告下载成功！',
        'link_copied' => '链接已复制到剪贴板！',
    ],
    'map_stats' => [
        'no_map_data' => '无地图数据',
        'share_title' => '我在 :map 的统计 - Faceit Scope',
        'share_text' => '查看我在 CS2 :map 地图上的表现！',
    ],
];
EOF

# ===============================
# AUTH.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: auth.php${NC}"
cat > "${TARGET_DIR}/auth.php" << 'EOF'
<?php
return [
    'buttons' => [
        'login' => '登录',
        'logout' => '退出',
        'profile' => '我的资料',
        'stats' => '我的统计',
        'user_menu' => '用户菜单',
    ],
    'status' => [
        'connected' => '已连接',
        'welcome' => '欢迎 :nickname！',
        'logout_success' => '成功退出',
        'profile_unavailable' => '资料数据不可用',
    ],
    'errors' => [
        'popup_blocked' => '无法打开弹窗。请检查弹窗是否被阻止。',
        'login_popup' => 'FACEIT 登录弹窗错误：:error',
        'login_failed' => '登录错误：:error',
        'logout_failed' => '退出时出错',
        'unknown_error' => '未知错误',
        'auth_init' => '认证初始化错误：',
        'auth_check' => '认证检查错误：',
    ],
    'console' => [
        'auth_status' => '认证状态：',
        'popup_opened' => 'FACEIT 弹窗已打开',
        'auth_result' => '收到认证结果：',
        'ui_updated' => 'UI 已更新：',
        'service_loaded' => '🔐 FACEIT 认证服务已加载',
    ],
];
EOF

# ===============================
# COMMON.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: common.php${NC}"
cat > "${TARGET_DIR}/common.php" << 'EOF'
<?php
return [
    'loading' => '加载中...',
    'error' => '错误',
    'success' => '成功',
    'retry' => '重试',
    'cancel' => '取消',
    'confirm' => '确认',
    'close' => '关闭',
    'search' => '搜索',
    'filter' => '筛选',
    'sort' => '排序',
    'refresh' => '刷新',
    'save' => '保存',
    'delete' => '删除',
    'edit' => '编辑',
    'view' => '查看',
    'today' => '今天',
    'yesterday' => '昨天',
    'days_ago' => ':count 天前',
    'weeks_ago' => ':count 周前',
    'months_ago' => ':count 月前',
    'no_data' => '无数据',
    'server_error' => '服务器错误。请稍后重试。',
    'network_error' => '连接错误。请检查您的网络连接。',
];
EOF

# ===============================
# COMPARISON.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: comparison.php${NC}"
cat > "${TARGET_DIR}/comparison.php" << 'EOF'
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
EOF

# ===============================
# CONTACT.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: contact.php${NC}"
cat > "${TARGET_DIR}/contact.php" << 'EOF'
<?php
return [
    'title' => '联系我们 - Faceit Scope',
    'hero' => [
        'title' => '联系我们',
    ],
    'sidebar' => [
        'developer' => [
            'title' => '开发者',
            'name_label' => '姓名',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => '回复',
            'average_delay' => '平均延迟',
            'delay_value' => '24小时',
        ],
    ],
    'form' => [
        'type' => [
            'label' => '消息类型',
            'required' => '*',
            'placeholder' => '选择类型',
            'options' => [
                'bug' => '报告错误',
                'suggestion' => '建议',
                'question' => '问题',
                'feedback' => '反馈',
                'other' => '其他',
            ],
        ],
        'subject' => [
            'label' => '主题',
            'required' => '*',
        ],
        'email' => [
            'label' => '邮箱',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit 用户名',
            'optional' => '（可选）',
        ],
        'message' => [
            'label' => '消息',
            'required' => '*',
            'character_count' => '字符',
        ],
        'submit' => [
            'send' => '发送',
            'sending' => '发送中...',
        ],
        'privacy_note' => '您的数据仅用于处理您的请求',
    ],
    'messages' => [
        'success' => [
            'title' => '消息发送成功',
            'ticket_id' => '工单 ID：',
        ],
        'error' => [
            'title' => '发送错误',
            'connection' => '连接错误。请重试。',
            'generic' => '发生错误。',
        ],
    ],
];
EOF

# ===============================
# ERRORS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: errors.php${NC}"
cat > "${TARGET_DIR}/errors.php" << 'EOF'
<?php
return [
    'page_not_found' => '页面未找到',
    'server_error' => '服务器错误',
    'unauthorized' => '未授权',
    'forbidden' => '禁止访问',
    'too_many_requests' => '请求过多',
];
EOF

# ===============================
# FOOTER.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: footer.php${NC}"
cat > "${TARGET_DIR}/footer.php" << 'EOF'
<?php
return [
    'about' => '关于我们',
    'privacy' => '隐私',
    'contact' => '联系',
    'data_provided' => '数据由 FACEIT API 提供。',
];
EOF

# ===============================
# FRIENDS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: friends.php${NC}"
cat > "${TARGET_DIR}/friends.php" << 'EOF'
<?php
return [
    'title' => '我的 FACEIT 好友',
    'subtitle' => '发现您的游戏圈子的表现',
    'load_more' => '显示更多 :count',
    'stats' => [
        'total' => '总计',
        'active_7d' => '活跃（7天）',
        'average_elo' => '平均 ELO',
        'best' => '最佳',
    ],
    'search_placeholder' => '搜索好友...',
    'activity_filter' => [
        'all' => '所有活动',
        'recent' => '最近（7天）',
        'month' => '本月',
        'inactive' => '不活跃（30天+）',
    ],
    'sort_by' => [
        'elo' => 'ELO',
        'activity' => '活动',
        'name' => '姓名',
        'level' => '等级',
    ],
    'loading' => [
        'title' => '加载好友中...',
        'connecting' => '连接中...',
        'fetching_friends' => '获取好友列表...',
        'loading_all' => '加载所有好友...',
        'finalizing' => '完成中...',
    ],
    'empty' => [
        'title' => '未找到好友',
        'description' => '您在 FACEIT 上还没有好友',
        'action' => '前往 FACEIT',
    ],
    'error' => [
        'title' => '加载错误',
        'not_authenticated' => '未认证',
        'missing_data' => '用户数据缺失',
        'load_failed' => '无法加载您的好友。请检查连接。',
        'server_error' => '服务器错误。请稍后重试。',
    ],
    'modal' => [
        'title' => '好友详情',
        'last_activity' => '最后活动',
        'elo_faceit' => 'FACEIT ELO',
        'view_faceit' => '在 FACEIT 查看',
        'view_stats' => '查看统计',
    ],
    'activity' => [
        'today' => '今天',
        'yesterday' => '昨天',
        'days_ago' => ':count 天前',
        'weeks_ago' => ':count 周前',
        'weeks_ago_plural' => ':count 周前',
        'months_ago' => ':count 月前',
        'no_recent' => '无近期活动',
    ],
    'count' => ':count 好友',
    'filtered_count' => '（显示 :count）',
    'load_more' => '显示更多 :count',
    'success_rate' => ':percentage% 成功',
    'friends_loaded' => '已加载 :loaded 好友，共 :total',
];
EOF

# ===============================
# HOME.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: home.php${NC}"
cat > "${TARGET_DIR}/home.php" << 'EOF'
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
EOF

# ===============================
# LANGUAGE.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: language.php${NC}"
cat > "${TARGET_DIR}/language.php" << 'EOF'
<?php
return [
    'changed_successfully' => '语言更改成功',
    'french' => 'Français',
    'english' => 'English',
    'spanish' => 'Español',
    'portuguese' => 'Português',
    'russian' => 'Русский',
    'italian' => 'Italiano',
    'chinese' => '中文',
];
EOF

# ===============================
# LEADERBOARDS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: leaderboards.php${NC}"
cat > "${TARGET_DIR}/leaderboards.php" << 'EOF'
<?php
return [
    'title' => 'CS2 全球排行榜 - Faceit Scope',
    'hero' => [
        'title' => 'CS2 排行榜',
        'subtitle' => '通过 FACEIT API 实时显示最佳玩家',
    ],
    'stats' => [
        'players' => '玩家',
        'average_elo' => '平均 ELO',
        'country' => '国家',
        'level' => '等级',
    ],
    'filters' => [
        'region' => '地区',
        'country' => '国家',
        'limit' => '限制',
        'refresh' => '刷新',
        'search' => '搜索',
        'regions' => [
            'EU' => '🌍 欧洲',
            'NA' => '🌎 北美',
            'SA' => '🌎 南美',
            'AS' => '🌏 亚洲',
            'AF' => '🌍 非洲',
            'OC' => '🌏 大洋洲',
        ],
        'countries' => [
            'all' => '全部',
        ],
        'limits' => [
            'top20' => '前 20',
            'top50' => '前 50',
            'top100' => '前 100',
        ],
        'refreshing' => '刷新中...',
        'close' => '关闭',
    ],
    'search' => [
        'title' => '搜索玩家',
        'placeholder' => 'FACEIT 玩家姓名...',
        'button' => '搜索',
        'searching' => '搜索中...',
        'searching_for' => '搜索 :player...',
        'errors' => [
            'empty_name' => '请输入玩家姓名',
            'not_found' => '未找到玩家 ":player"',
            'no_cs2_profile' => '玩家 ":player" 没有 CS2 资料',
            'timeout' => '搜索过慢，请重试...',
        ],
    ],
    'loading' => [
        'title' => '加载中...',
        'progress' => '连接 FACEIT API',
        'players_enriched' => ':count 玩家已丰富...',
        'details' => '加载中...',
    ],
    'error' => [
        'title' => '加载错误',
        'default_message' => '发生错误',
        'retry' => '重试',
        'no_players' => '此排行榜未找到玩家',
    ],
    'leaderboard' => [
        'title' => '全球排行榜',
        'updated_now' => '刚刚更新',
        'updated_on' => '更新于 :date :time',
        'table' => [
            'rank' => '#',
            'player' => '玩家',
            'stats' => '',
            'elo' => 'ELO',
            'level' => '等级',
            'form' => '状态',
            'actions' => '操作',
        ],
        'pagination' => [
            'previous' => '上一页',
            'next' => '下一页',
            'page' => '第 :page 页',
            'players' => '玩家 :start-:end',
        ],
        'region_names' => [
            'EU' => '欧洲',
            'NA' => '北美',
            'SA' => '南美',
            'AS' => '亚洲',
            'AF' => '非洲',
            'OC' => '大洋洲',
        ],
        'country_names' => [
            'FR' => '法国',
            'DE' => '德国',
            'GB' => '英国',
            'ES' => '西班牙',
            'IT' => '意大利',
            'US' => '美国',
            'CA' => '加拿大',
            'BR' => '巴西',
            'RU' => '俄罗斯',
            'PL' => '波兰',
            'SE' => '瑞典',
            'DK' => '丹麦',
            'NO' => '挪威',
            'FI' => '芬兰',
            'NL' => '荷兰',
            'BE' => '比利时',
            'CH' => '瑞士',
            'AT' => '奥地利',
            'CZ' => '捷克',
            'UA' => '乌克兰',
            'TR' => '土耳其',
            'CN' => '中国',
            'KR' => '韩国',
            'JP' => '日本',
        ],
    ],
    'player' => [
        'position_region' => ':region 位置',
        'stats_button' => '统计',
        'compare_button' => '对比',
        'view_stats' => '查看统计',
        'private_stats' => '私人',
        'level_short' => '等级 :level',
    ],
    'form' => [
        'excellent' => '优秀',
        'good' => '良好',
        'average' => '一般',
        'poor' => '较差',
        'unknown' => '未知',
    ],
];
EOF

# ===============================
# MESSAGES.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: messages.php${NC}"
cat > "${TARGET_DIR}/messages.php" << 'EOF'
<?php
return [
    'welcome' => '欢迎',
    'goodbye' => '再见',
    'thank_you' => '谢谢',
    'please_wait' => '请稍等',
    'operation_successful' => '操作成功',
    'operation_failed' => '操作失败',
];
EOF

# ===============================
# NAVIGATION.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: navigation.php${NC}"
cat > "${TARGET_DIR}/navigation.php" << 'EOF'
<?php
return [
    'home' => '首页',
    'friends' => '好友',
    'comparison' => '对比',
    'leaderboards' => '排行榜',
    'tournaments' => '锦标赛',
    'profile' => '资料',
    'login' => '登录',
    'logout' => '退出',
    'settings' => '设置',
    'about' => '关于我们',
    'contact' => '联系',
    'privacy' => '隐私',
];
EOF

# ===============================
# PRIVACY.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: privacy.php${NC}"
cat > "${TARGET_DIR}/privacy.php" << 'EOF'
<?php
return [
    'title' => '隐私政策 - Faceit Scope',
    'header' => [
        'title' => '隐私政策',
        'subtitle' => 'Faceit Scope 扩展',
        'last_updated' => '最后更新：2025年7月23日',
    ],
    'introduction' => [
        'title' => '1. 介绍',
        'content' => 'Faceit Scope 是一个浏览器扩展，分析 FACEIT 的 CS2 比赛以显示统计数据和预测。我们尊重您的隐私并致力于保护您的个人数据。',
    ],
    'data_collected' => [
        'title' => '2. 收集的数据',
        'temporary_data' => [
            'title' => '2.1 临时处理的数据（不存储）',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'FACEIT 公开用户名：',
                    'description' => '已在 FACEIT 上公开显示的游戏昵称，临时读取用于分析',
                ],
                'public_stats' => [
                    'title' => '公开游戏统计：',
                    'description' => 'K/D、胜率、已玩地图（通过 FACEIT 公共 API）',
                ],
                'match_ids' => [
                    'title' => '比赛 ID：',
                    'description' => '从 URL 提取以识别要分析的比赛',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 本地存储的数据（仅临时缓存）',
            'items' => [
                'analysis_results' => [
                    'title' => '分析结果：',
                    'description' => '在您的设备上最多存储 5 分钟，以避免重复的 API 调用',
                ],
                'user_preferences' => [
                    'title' => '用户偏好：',
                    'description' => '扩展设置（启用/禁用通知）',
                ],
            ],
        ],
        'important_note' => '重要：不收集或保存个人身份识别数据。所有处理的数据在 FACEIT 上已经是公开的。',
    ],
    'data_usage' => [
        'title' => '3. 数据使用',
        'description' => '收集的数据仅用于：',
        'items' => [
            'display_stats' => '在 FACEIT 界面显示玩家统计',
            'predictions' => '计算获胜队伍预测',
            'map_recommendations' => '为队伍推荐最佳/最差地图',
            'performance' => '通过临时缓存提升性能',
        ],
    ],
    'data_sharing' => [
        'title' => '4. 数据共享',
        'no_third_party' => [
            'title' => '4.1 不与第三方共享',
            'items' => [
                'no_selling' => '我们不向第三方出售任何数据',
                'no_transfer' => '我们不传输任何个人数据',
                'local_analysis' => '所有分析都在您的浏览器中本地执行',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => '扩展仅使用官方 FACEIT 公共 API',
                'no_private_data' => '不收集私人或敏感数据',
                'public_stats' => '使用的所有统计数据都是公开可访问的',
            ],
        ],
    ],
    'security' => [
        'title' => '5. 安全和保留',
        'local_storage' => [
            'title' => '5.1 仅本地存储',
            'items' => [
                'local_only' => '所有数据都存储在您的设备本地',
                'no_server_transmission' => '没有数据传输到我们的服务器',
                'auto_delete' => '缓存在 5 分钟后自动删除',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 有限访问',
            'items' => [
                'faceit_only' => '扩展仅访问您访问的 FACEIT 页面',
                'no_other_access' => '不访问其他网站或个人数据',
                'no_tracking' => '不跟踪您的浏览',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. 您的权利',
        'data_control' => [
            'title' => '6.1 数据控制',
            'items' => [
                'clear_cache' => '您可以随时通过扩展弹窗清除缓存',
                'uninstall' => '您可以卸载扩展以删除所有数据',
                'disable_notifications' => '您可以在设置中禁用通知',
            ],
        ],
        'public_data' => [
            'title' => '6.2 公开数据',
            'items' => [
                'already_public' => '所有分析的数据在 FACEIT 上已经是公开的',
                'no_private_info' => '扩展不透露任何私人信息',
                'no_personal_data' => '不收集个人身份识别数据',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookie 和跟踪技术',
        'description' => 'Faceit Scope 扩展：',
        'does_not_use' => [
            'title' => '不使用：',
            'items' => [
                'no_cookies' => '无 Cookie',
                'no_ad_tracking' => '无广告跟踪',
                'no_behavioral_analysis' => '无行为分析',
            ],
        ],
        'uses_only' => [
            'title' => '仅使用：',
            'items' => [
                'local_storage' => '浏览器本地存储',
                'temp_cache' => '临时缓存（最多 5 分钟）',
                'public_api' => 'FACEIT 公共 API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. 此政策的更新',
        'content' => '我们可能会更新此隐私政策。更改将发布在此页面上，如有必要，将通过扩展更新通知您。',
    ],
    'contact' => [
        'title' => '9. 联系方式',
        'description' => '如对此隐私政策有任何疑问：',
        'website' => '网站：',
        'email' => '邮箱：',
    ],
    'compliance' => [
        'title' => '10. 法规合规',
        'description' => '此扩展符合：',
        'items' => [
            'gdpr' => '通用数据保护条例 (GDPR)',
            'chrome_store' => 'Chrome 网上应用店政策',
            'faceit_terms' => 'FACEIT API 使用条款',
        ],
    ],
];
EOF

# ===============================
# TOURNAMENTS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: tournaments.php${NC}"
cat > "${TARGET_DIR}/tournaments.php" << 'EOF'
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
EOF

echo ""
echo -e "${GREEN}🎉 Traduction chinoise terminée avec succès !${NC}"
echo -e "${BLUE}📁 Fichiers créés dans : $TARGET_DIR${NC}"
echo ""
echo -e "${YELLOW}📋 Résumé des fichiers traduits :${NC}"
ls -la "$TARGET_DIR" | grep -E "\.php$" | wc -l | xargs echo "Total des fichiers : "
echo ""
echo -e "${GREEN}✅ Tous les fichiers ont été traduits en chinois${NC}"
echo -e "${BLUE}🚀 Prêt à être utilisé dans votre application Laravel${NC}"
echo ""

# Vérifier que tous les fichiers ont été créés correctement
echo -e "${YELLOW}🔍 Vérification des fichiers créés :${NC}"
for file in "${FILES_TO_TRANSLATE[@]}"; do
    if [ -f "${TARGET_DIR}/${file}" ]; then
        echo -e "${GREEN}✅ $file${NC}"
    else
        echo -e "${RED}❌ $file (manquant)${NC}"
    fi
done

echo ""
echo -e