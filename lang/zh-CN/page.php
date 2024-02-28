<?php

return [
    'general_settings' => [
        'title' => '常规设置',
        'heading' => '常规设置',
        'subheading' => '在此管理常规站点设置。',
        'navigationLabel' => '一般的',
        'sections' => [
            'site' => [
                'title' => '地点',
                'description' => '管理基本设置。',
            ],
            'theme' => [
                'title' => '主题',
                'description' => '更改默认主题。',
            ],
        ],
        'fields' => [
            'brand_name' => '品牌',
            'site_active' => '站点状态',
            'brand_logoHeight' => '品牌标志高度',
            'brand_logo' => '品牌标志',
            'site_favicon' => '网站图标',
            'primary' => '基本的',
            'secondary' => '中学',
            'gray' => '灰色的',
            'success' => '成功',
            'danger' => '危险',
            'info' => '信息',
            'warning' => '警告',
        ],
    ],
    'mail_settings' => [
        'title' => '邮件设置',
        'heading' => '邮件设置',
        'subheading' => '管理邮件配置。',
        'navigationLabel' => '邮件',
        'sections' => [
            'config' => [
                'title' => '配置',
                'description' => '描述',
            ],
            'sender' => [
                'title' => '来自（发件人）',
                'description' => '描述',
            ],
            'mail_to' => [
                'title' => '邮寄至',
                'description' => '描述',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => '收件人邮箱..',
            ],
            'driver' => '司机',
            'host' => '主持人',
            'port' => '港口',
            'encryption' => '加密',
            'timeout' => '暂停',
            'username' => '用户名',
            'password' => '密码',
            'email' => '电子邮件',
            'name' => '姓名',
            'mail_to' => '邮寄至',
        ],
        'actions' => [
            'send_test_mail' => '发送测试邮件',
        ],
    ]
    ];
