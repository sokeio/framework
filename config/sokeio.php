<?php

use Sokeio\Console\DebugCommand;

return [
    'version' => '1.0.0',
    'admin_url' => env('PLATFORM_URL_ADMIN', '/'),
    'admin_theme' => env('PLATFORM_THEME_ADMIN', 'sokeio/theme-admin'),
    'site_theme' => env('PLATFORM_THEME_SITE', 'sokeio/theme-cms'),
    'updator' => [
        'url' =>  env('PLATFORM_UPDATOR_URL', 'https://updator.sokeio.com/'),
        'temps' => env('PLATFORM_UPDATOR_TEMP', 'temps')
    ],

    'model' => [
        'user' => Sokeio\Models\User::class,
        'role' => Sokeio\Models\Role::class,
        'permission' => Sokeio\Models\Permission::class,
    ],
    'commands' => [
        DebugCommand::class
    ],
    'platform' => [
        'module' => [
            'path' => base_path('platform/module'),
            'public' => base_path('public/platform/module'),
            'namespace' => 'Sokeio\\Module'
        ],
        'theme' => [
            'path' => base_path('platform/theme'),
            'public' => base_path('public/platform/theme'),
            'namespace' => 'Sokeio\\Theme'
        ],
    ],
    'composer' => [
        'vendor' => env('PLATFORM_VENDOR', 'sokeio'),
        'author' => [
            'name' => env('PLATFORM_AUTHOR_NAME', 'Nguyen Van Hau'),
            'email' => env('PLATFORM_AUTHOR_EMAIL', 'nguyenvanhaudev@gmail.com'),
        ],
        'composer-output' => false,
    ],
];
