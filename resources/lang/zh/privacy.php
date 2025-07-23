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
