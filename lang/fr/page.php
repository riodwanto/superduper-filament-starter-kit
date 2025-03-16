<?php

return [
    'general_settings' => [
        'title' => 'Paramètres généraux',
        'heading' => 'Paramètres généraux',
        'subheading' => 'Gérez ici les paramètres du site général.',
        'navigationLabel' => 'Général',
        'sections' => [
            'site' => [
                'title' => 'Site',
                'description' => 'Gérer les paramètres de base.',
            ],
            'theme' => [
                'title' => 'Thème',
                'description' => 'Modifier le thème par défaut.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Nom de marque',
            'site_active' => 'Statut du site',
            'brand_logoHeight' => 'Hauteur du logo de la marque',
            'brand_logo' => 'Logo de marque',
            'site_favicon' => 'Site favicon',
            'primary' => 'Primaire',
            'secondary' => 'Secondaire',
            'gray' => 'Gris',
            'success' => 'Succès',
            'danger' => 'Danger',
            'info' => 'Informations',
            'warning' => 'Avertissement',
        ],
    ],
    'mail_settings' => [
        'title' => 'Paramètres de messagerie',
        'heading' => 'Paramètres de messagerie',
        'subheading' => 'Gérer la configuration du courrier.',
        'navigationLabel' => 'Mail',
        'sections' => [
            'config' => [
                'title' => 'Configuration',
                'description' => 'description',
            ],
            'sender' => [
                'title' => 'De (expéditeur)',
                'description' => 'description',
            ],
            'mail_to' => [
                'title' => 'Envoyez-vous à',
                'description' => 'description',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'Email du récepteur ..',
            ],
            'driver' => 'Conducteur',
            'host' => 'Hôte',
            'port' => 'Port',
            'encryption' => 'Cryptage',
            'timeout' => 'Temps mort',
            'username' => 'Nom d\'utilisateur',
            'password' => 'Mot de passe',
            'email' => 'E-mail',
            'name' => 'Nom',
            'mail_to' => 'Envoyez-vous à',
        ],
        'actions' => [
            'send_test_mail' => 'Envoyer le courrier de test',
        ],
    ]
    ];
