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
        'ADR' => 'ADR',
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
