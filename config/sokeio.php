<?php

use Sokeio\Console\DebugCommand;

return [
    'admin_url' => env('PLATFORM_URL_ADMIN', 'admin'),
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
