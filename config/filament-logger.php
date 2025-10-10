<?php
return [
    'datetime_format' => 'd/m/Y H:i:s',
    'date_format' => 'd/m/Y',

    'activity_resource' => \Riodwanto\FilamentLogger\Resources\ActivityResource::class,
	'scoped_to_tenant' => true,
	'navigation_sort' => null,

    'resources' => [
        'enabled' => true,
        'log_name' => 'Resource',
        'logger' => \Riodwanto\FilamentLogger\Loggers\ResourceLogger::class,
        'color' => 'warning',

        'exclude' => [
            //App\Filament\Resources\UserResource::class,
        ],
        'cluster' => null,
        'navigation_group' =>'Settings',
    ],

    'access' => [
        'enabled' => true,
        'logger' => \Riodwanto\FilamentLogger\Loggers\AccessLogger::class,
        'color' => 'warning',
        'log_name' => 'Access',
    ],

    'notifications' => [
        'enabled' => true,
        'logger' => \Riodwanto\FilamentLogger\Loggers\NotificationLogger::class,
        'color' => null,
        'log_name' => 'Notification',
    ],

    'models' => [
        'enabled' => true,
        'log_name' => 'Model',
        'color' => 'warning',
        'logger' => \Riodwanto\FilamentLogger\Loggers\ModelLogger::class,
        'register' => [
            //App\Models\User::class,
        ],
    ],

    'custom' => [
        // [
        //     'log_name' => 'Custom',
        //     'color' => 'primary',
        // ]
    ],
];
