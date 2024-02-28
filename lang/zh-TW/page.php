<?php

return [
    'general_settings' => [
        'title' => '常規設定',
        'heading' => '常規設定',
        'subheading' => '在此管理常規站點設定。',
        'navigationLabel' => '一般的',
        'sections' => [
            'site' => [
                'title' => '地點',
                'description' => '管理基本設定。',
            ],
            'theme' => [
                'title' => '主題',
                'description' => '更改預設主題。',
            ],
        ],
        'fields' => [
            'brand_name' => '品牌',
            'site_active' => '站點狀態',
            'brand_logoHeight' => '品牌標誌高度',
            'brand_logo' => '品牌標誌',
            'site_favicon' => '網站圖示',
            'primary' => '基本的',
            'secondary' => '中學',
            'gray' => '灰色的',
            'success' => '成功',
            'danger' => '危險',
            'info' => '資訊',
            'warning' => '警告',
        ],
    ],
    'mail_settings' => [
        'title' => '郵件設定',
        'heading' => '郵件設定',
        'subheading' => '管理郵件配置。',
        'navigationLabel' => '郵件',
        'sections' => [
            'config' => [
                'title' => '配置',
                'description' => '描述',
            ],
            'sender' => [
                'title' => '來自（寄件者）',
                'description' => '描述',
            ],
            'mail_to' => [
                'title' => '郵寄至',
                'description' => '描述',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => '收件者信箱..',
            ],
            'driver' => '司機',
            'host' => '主持人',
            'port' => '港口',
            'encryption' => '加密',
            'timeout' => '暫停',
            'username' => '使用者名稱',
            'password' => '密碼',
            'email' => '電子郵件',
            'name' => '姓名',
            'mail_to' => '郵寄至',
        ],
        'actions' => [
            'send_test_mail' => '發送測試郵件',
        ],
    ]
    ];
