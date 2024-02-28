<?php

return [
    'general_settings' => [
        'title' => 'Configurações Gerais',
        'heading' => 'Configurações Gerais',
        'subheading' => 'Gerencie as configurações gerais do site aqui.',
        'navigationLabel' => 'Em geral',
        'sections' => [
            'site' => [
                'title' => 'Site',
                'description' => 'Gerenciar configurações básicas.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Altere o tema padrão.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Marca',
            'site_active' => 'Status do site',
            'brand_logoHeight' => 'Altura do logotipo da marca',
            'brand_logo' => 'Logotipo da marca',
            'site_favicon' => 'Favicon do site',
            'primary' => 'Primário',
            'secondary' => 'Secundário',
            'gray' => 'Cinza',
            'success' => 'Sucesso',
            'danger' => 'Perigo',
            'info' => 'Informações',
            'warning' => 'Aviso',
        ],
    ],
    'mail_settings' => [
        'title' => 'Configurações de correio',
        'heading' => 'Configurações de correio',
        'subheading' => 'Gerenciar configuração de email.',
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
                'title' => 'Enviar para',
                'description' => 'descrição',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'E-mail do destinatário..',
            ],
            'driver' => 'Motorista',
            'host' => 'Hospedar',
            'port' => 'Porta',
            'encryption' => 'Criptografia',
            'timeout' => 'Tempo esgotado',
            'username' => 'Nome de usuário',
            'password' => 'Senha',
            'email' => 'E-mail',
            'name' => 'Nome',
            'mail_to' => 'Enviar para',
        ],
        'actions' => [
            'send_test_mail' => 'Enviar e-mail de teste',
        ],
    ]
    ];
