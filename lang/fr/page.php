<?php

return [
    'general_settings' => [
        'title' => 'réglages généraux',
        'heading' => 'réglages généraux',
        'subheading' => 'Gérez les paramètres généraux du site ici.',
        'navigationLabel' => 'Général',
        'sections' => [
            'site' => [
                'title' => 'Site',
                'description' => 'Gérer les paramètres de base.',
            ],
            'theme' => [
                'title' => 'Thème',
                'description' => 'Changer le thème par défaut.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Marque',
            'site_active' => 'Statut du site',
            'brand_logoHeight' => 'Hauteur du logo de la marque',
            'brand_logo' => 'Logo de la marque',
            'site_favicon' => 'Icône de favori du site',
            'primary' => 'Primaire',
            'secondary' => 'Secondaire',
            'gray' => 'Gris',
            'success' => 'Succès',
            'danger' => 'Danger',
            'info' => 'Info',
            'warning' => 'Avertissement',
        ],
    ],
    'mail_settings' => [
        'title' => 'Paramètres de messagerie',
        'heading' => 'Paramètres de messagerie',
        'subheading' => 'Gérer la configuration de la messagerie.',
        'navigationLabel' => 'Mail',
        'sections' => [
            'config' => [
                'title' => 'Configuration',
                'description' => 'description',
            ],
            'sender' => [
                'title' => 'De (Expéditeur)',
                'description' => 'description',
            ],
            'mail_to' => [
                'title' => 'Envoyer à',
                'description' => 'description',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'E-mail du destinataire..',
            ],
            'driver' => 'Conducteur',
            'host' => 'Hôte',
            'port' => 'Port',
            'encryption' => 'Chiffrement',
            'timeout' => 'Temps mort',
            'username' => 'Nom d\'utilisateur',
            'password' => 'Mot de passe',
            'email' => 'E-mail',
            'name' => 'Nom',
            'mail_to' => 'Envoyer à',
        ],
        'actions' => [
            'send_test_mail' => 'Envoyer un courrier test',
        ],
    ]
    ];
