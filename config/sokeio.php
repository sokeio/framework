<?php

use Sokeio\Console\Commands\DebugCommand;
use Sokeio\Console\Commands\FileCommand;
use Sokeio\Console\Commands\InstallCommand;
use Sokeio\Console\Commands\MakeCommand;
use Sokeio\Console\Commands\SystemUpdateCommand;

return [
    'version' => '1.0.0',
    'admin_url' => env('PLATFORM_URL_ADMIN', 'admin'),
    'admin_theme' => env('PLATFORM_THEME_ADMIN', 'sokeio/theme-admin'),
    'site_theme' => env('PLATFORM_THEME_SITE', 'sokeio/theme-content'),
    'model' => [
        'user' => Sokeio\Models\User::class,
        'role' => Sokeio\Models\Role::class,
        'permission' => Sokeio\Models\Permission::class,
    ],
    'commands' => [
        DebugCommand::class,
        MakeCommand::class,
        FileCommand::class,
        SystemUpdateCommand::class,
        InstallCommand::class
    ],
    'platform' => [
        'token_prefix' => env('PLATFORM_TOKEN_PREFIX', 'sokeio-'),
        'product' => base_path('platform/product.json'),
        'marketplace' => env('PLATFORM_MARKETPLACE_API', 'https://sokeio.com/api/'),
        'updater' => [
            'backup' => base_path(env('PLATFORM_UPDATER_BACKUP', 'platform/updater/backups/')),
            'temp' => base_path(env('PLATFORM_UPDATER_BACKUP', 'platform/updater/temps/'))
        ],
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
