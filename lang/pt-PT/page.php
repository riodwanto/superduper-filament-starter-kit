<?php

return [
    'general_settings' => [
        'title' => 'Configurações Gerais',
        'heading' => 'Configurações Gerais',
        'subheading' => 'Gira as configurações gerais do site aqui.',
        'navigationLabel' => 'General',
        'sections' => [
            'site' => [
                'title' => 'Local',
                'description' => 'Gerir configurações básicas.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Altere o tema predefinido.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Marca',
            'site_active' => 'Estado do site',
            'brand_logoHeight' => 'Altura do logótipo da marca',
            'brand_logo' => 'Logótipo da marca',
            'site_favicon' => 'Favicon do site',
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
        'subheading' => 'Gerir configuração de email.',
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
            'host' => 'Anfitrião',
            'port' => 'Porto',
            'encryption' => 'Criptografia',
            'timeout' => 'Tempo esgotado',
            'username' => 'Nome de utilizador',
            'password' => 'Palavra-passe',
            'email' => 'E-mail',
            'name' => 'Nome',
            'mail_to' => 'Enviar para',
        ],
        'actions' => [
            'send_test_mail' => 'Enviar e-mail de teste',
        ],
    ]
    ];
