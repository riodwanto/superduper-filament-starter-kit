<?php

return [
    'general_settings' => [
        'title' => 'Configurações gerais',
        'heading' => 'Configurações gerais',
        'subheading' => 'Gerencie as configurações gerais do site aqui.',
        'navigationLabel' => 'General',
        'sections' => [
            'site' => [
                'title' => 'Local',
                'description' => 'Gerenciar configurações básicas.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Alterar tema padrão.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Nome da marca',
            'site_active' => 'Status do site',
            'brand_logoHeight' => 'Altura do logotipo da marca',
            'brand_logo' => 'Logotipo da marca',
            'site_favicon' => 'Site Favicon',
            'primary' => 'Primário',
            'secondary' => 'Secundário',
            'gray' => 'Cinzento',
            'success' => 'Sucesso',
            'danger' => 'Perigo',
            'info' => 'Informações',
            'warning' => 'Aviso',
        ],
    ],
    'mail_settings' => [
        'title' => 'Configurações de correio',
        'heading' => 'Configurações de correio',
        'subheading' => 'Gerencie a configuração de email.',
        'navigationLabel' => 'Correspondência',
        'sections' => [
            'config' => [
                'title' => 'Configuração',
                'description' => 'descrição',
            ],
            'sender' => [
                'title' => 'De (remetente)',
                'description' => 'descrição',
            ],
            'mail_to' => [
                'title' => 'Correio para',
                'description' => 'descrição',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'E -mail do receptor ..',
            ],
            'driver' => 'Motorista',
            'host' => 'Anfitrião',
            'port' => 'Porto',
            'encryption' => 'Criptografia',
            'timeout' => 'Tempo esgotado',
            'username' => 'Nome de usuário',
            'password' => 'Palavra-passe',
            'email' => 'E-mail',
            'name' => 'Nome',
            'mail_to' => 'Correio para',
        ],
        'actions' => [
            'send_test_mail' => 'Enviar e -mail de teste',
        ],
    ]
    ];
