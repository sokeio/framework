<?php

use Sokeio\Console\DebugCommand;

return [
    'admin_url' => env('PLATFORM_URL_ADMIN', 'admin'),
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
    ]
];
