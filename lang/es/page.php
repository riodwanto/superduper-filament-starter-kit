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
                'description' => 'Administrar la configuración básica.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Cambiar el tema predeterminado.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Nombre de la marca',
            'site_active' => 'Estado del sitio',
            'brand_logoHeight' => 'Altura del logotipo de la marca',
            'brand_logo' => 'Logotipo',
            'site_favicon' => 'Favicón del sitio',
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
        'subheading' => 'Gestionar la configuración del correo.',
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
                'title' => 'Enviar por correo a',
                'description' => 'descripción',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'Correo electrónico del receptor..',
            ],
            'driver' => 'Conductor',
            'host' => 'Anfitrión',
            'port' => 'Puerto',
            'encryption' => 'Cifrado',
            'timeout' => 'Se acabó el tiempo',
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'email' => 'Correo electrónico',
            'name' => 'Nombre',
            'mail_to' => 'Enviar por correo a',
        ],
        'actions' => [
            'send_test_mail' => 'Enviar correo de prueba',
        ],
    ]
    ];
