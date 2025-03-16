<?php

return [
    'general_settings' => [
        'title' => 'Configuración general',
        'heading' => 'Configuración general',
        'subheading' => 'Administre la configuración general del sitio aquí.',
        'navigationLabel' => 'General',
        'sections' => [
            'site' => [
                'title' => 'Sitio',
                'description' => 'Administrar configuraciones básicas.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Cambiar el tema predeterminado.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Marca',
            'site_active' => 'Estado del sitio',
            'brand_logoHeight' => 'Altura del logotipo de la marca',
            'brand_logo' => 'Logotipo de la marca',
            'site_favicon' => 'Sitio favicon',
            'primary' => 'Primario',
            'secondary' => 'Secundario',
            'gray' => 'Gris',
            'success' => 'Éxito',
            'danger' => 'Peligro',
            'info' => 'Información',
            'warning' => 'Advertencia',
        ],
    ],
    'mail_settings' => [
        'title' => 'Configuración de correo',
        'heading' => 'Configuración de correo',
        'subheading' => 'Administrar la configuración del correo.',
        'navigationLabel' => 'Correo',
        'sections' => [
            'config' => [
                'title' => 'Configuración',
                'description' => 'descripción',
            ],
            'sender' => [
                'title' => 'De (remitente)',
                'description' => 'descripción',
            ],
            'mail_to' => [
                'title' => 'Enviar',
                'description' => 'descripción',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'Correo electrónico del receptor ..',
            ],
            'driver' => 'Conductor',
            'host' => 'Anfitrión',
            'port' => 'Puerto',
            'encryption' => 'Encriptación',
            'timeout' => 'Se acabó el tiempo',
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'email' => 'Correo electrónico',
            'name' => 'Nombre',
            'mail_to' => 'Enviar',
        ],
        'actions' => [
            'send_test_mail' => 'Enviar correo de prueba',
        ],
    ]
    ];
