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
