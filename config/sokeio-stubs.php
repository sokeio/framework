<?php
return [
    'enabled' => false,
    'path' => base_path('vendor/sokeio/framework/stubs'),
    'paths' => [
        'base' => ['path' => '', 'namespace' => '', 'generate' => false],
        'src' => ['path' => 'src', 'namespace' => '', 'generate' => false],
        'module-theme' => ['path' => 'theme', 'generate' => true, 'only' => ['module']],
        'config' => ['path' => 'config', 'generate' => true, 'only' => ['module']],
        'command' => [
            'path' => 'src/Console/Commands',
            'namespace' => 'Console/Commands',
            'generate' => true,
            'only' => ['module']
        ],
        'observer' => [
            'path' => 'src/Observer',
            'namespace' => 'Observer',
            'generate' => true,
            'only' => ['module']
        ],
        'migration' => [
            'path' => 'database/migrations',
            'namespace' => 'Database\\Migrations',
            'generate' => true,
            'only' => ['module']
        ],
        'seeder' => [
            'path' => 'database/seeders',
            'namespace' => 'Database\\Seeders',
            'generate' => true,
            'only' => ['module']
        ],
        'factory' => [
            'path' => 'database/factories',
            'namespace' => 'Database\\Factories',
            'generate' => true,
            'only' => ['module']
        ],
        'model' => ['path' => 'src/Models', 'namespace' => 'Models', 'generate' => true],
        'routes' => ['path' => 'routes', 'generate' => true],
        // 'routes-admin' => ['path' => 'routes/admin', 'generate' => true],
        // 'routes-web' => ['path' => 'routes/web', 'generate' => true],
        'controller' => [
            'path' => 'src/Http/Controllers',
            'namespace' => 'Http\\Controllers',
            'generate' => true
        ],
        'middleware' => [
            'path' => 'src/Http/Middleware',
            'namespace' => 'Http\\Middleware',
            'generate' => true
        ],
        'request' => [
            'path' => 'src/Http/Requests',
            'namespace' => 'Http\\Requests',
            'generate' => true
        ],
        'services' => [
            'path' => 'src/Http/Services',
            'namespace' => 'Http\\Services',
            'generate' => true
        ],
        'action' => [
            'path' => 'src/Actions',
            'namespace' => 'Actions',
            'generate' => true,
            'only' => ['module']
        ],

        'page' => ['path' => 'src/Page', 'namespace' => 'Page', 'generate' => true],
        'widget' => ['path' => 'src/Widget', 'namespace' => 'Widget', 'generate' => true],
        'livewire' => ['path' => 'src/Livewire', 'namespace' => 'Livewire', 'generate' => true],
        'provider' => ['path' => 'src/Providers', 'namespace' => 'Providers', 'generate' => true],
        'helpers' => ['path' => 'helpers', 'generate' => true],
        'assets' => ['path' => 'resources', 'generate' => true],
        'lang' => ['path' => 'resources/lang', 'generate' => true],
        'views' => ['path' => 'resources/views', 'generate' => true],
        'view-layouts' => ['path' => 'resources/views/layouts', 'generate' => true, 'only' => ['theme']],
        'view-common' => ['path' => 'resources/views/common', 'generate' => true, 'only' => ['theme']],
        'view-errors' => ['path' => 'resources/views/errors', 'generate' => true, 'only' => ['theme']],
        'view-pages' => ['path' => 'resources/views/pages', 'generate' => true, 'only' => ['theme']],
        'view-scope' => ['path' => 'resources/views/scope', 'generate' => true, 'only' => ['theme']],
        'view-share' => ['path' => 'resources/views/shared', 'generate' => true, 'only' => ['theme']],
        'public' => ['path' => 'public', 'generate' => true],
        'test' => ['path' => 'Tests/Unit', 'namespace' => 'Tests\\Unit', 'generate' => true, 'only' => ['module']],
        'test-feature' => [
            'path' => 'Tests/Feature',
            'namespace' => 'Feature',
            'generate' => true,
            'only' => ['module']
        ],
        'repository' => [
            'path' => 'src/Repositories',
            'namespace' => 'Repositories',
            'generate' => false,
            'only' => ['module']
        ],
        'event' => ['path' => 'src/Events', 'namespace' => 'Events', 'generate' => false, 'only' => ['module']],
        'listener' => [
            'path' => 'src/Listeners',
            'namespace' => 'Listeners',
            'generate' => false,
            'only' => ['module']
        ],
        'policies' => ['path' => 'src/Policies', 'namespace' => 'Policies', 'generate' => false, 'only' => ['module']],
        'rules' => ['path' => 'src/Rules', 'namespace' => 'Rules', 'generate' => false, 'only' => ['module']],
        'jobs' => ['path' => 'src/Jobs', 'namespace' => 'Jobs', 'generate' => false, 'only' => ['module']],
        'emails' => ['path' => 'src/Emails', 'namespace' => 'Emails', 'generate' => false, 'only' => ['module']],
        'notifications' => [
            'path' => 'src/Notifications',
            'namespace' => 'Notifications',
            'generate' => false,
            'only' => ['module']
        ],
        'resource' => [
            'path' => 'src/Transformers',
            'namespace' => 'Transformers',
            'generate' => false,
            'only' => ['module']
        ],
        'component-view' => [
            'path' => 'resources/views/components',
            'generate' => false,
            'only' => ['module']
        ],
        'component-class' => [
            'path' => 'src/View/Components',
            'namespace' => 'View\\Components',
            'generate' => false,
            'only' => ['module']
        ],
    ],
    'list-files' => [
        'common' => [
            "view",
            "route",
            // "api",
            "admin",
            "function",
            "command",
            "observer",
            "component-class",
            "component-view",
            "controller-api",
            "controller-plain",
            "controller",
            "event",
            "job",
            "job-queued",
            "model",
            "listener",
            "listener-queued",
            "listener-queued-duck",
            "listener-duck",
            "mail",
            "notification",
            "policy-plain",
            "provider",
            "route-provider",
            "request",
            "services",
            "resource",
            "resource-collection",
            "rule",
            "seeder",
            "action",

        ],
        'module' => [
            'route',
            'api',
            'admin',
            "migration-create",
            "migration-add",
            "migration-delete",
            "migration-drop",
            "migration",
            "middleware",
            "factory",
        ],
        'theme' => []
    ],
    'templates' => [
        'index-html' => [
            'path' => 'public',
            'name' => 'index.html'
        ],
        'config' => [
            'stub' => 'scaffold/config',
            'path' => 'config',
            'name' => '$LOWER_NAME$.php',
            'replacements' => [
                'LOWER_NAME',
                'STUDLY_NAME'
            ]
        ],
        'option' => [
            'stub' => 'option',
            'path' => 'src',
            'name' => 'Option.php',
            'replacements' => ['NAMESPACE']
        ],
        'view' => [
            'stub' => 'views/index',
            'path' => 'views',
            'name' => 'index.blade.php'
        ],
        'layout' => [
            'stub' => 'views/layout',
            'path' => 'view-layouts',
            'name' => 'layout.blade.php'
        ],
        'layout-none' => [
            'stub' => 'views/layout-none',
            'path' => 'view-layouts',
            'name' => 'none.blade.php'
        ],
        'layout-default' => [
            'stub' => 'views/layout-none',
            'path' => 'view-layouts',
            'name' => 'default.blade.php'
        ],
        'layout-name' => [
            'stub' => 'views/layout-name',
            'path' => 'view-layouts',
            'name' => '$LOWER_NAME$.blade.php'
        ],
        'share-header' => [
            'stub' => 'views/empty',
            'path' => 'view-share',
            'name' => 'header.blade.php'
        ],
        'share-footer' => [
            'stub' => 'views/empty',
            'path' => 'view-share',
            'name' => 'footer.blade.php'
        ],
        'app-js' => [
            'stub' => 'assets/js/app',
            'path' => 'assets',
            'name' => 'js/app.js'
        ],
        'app-sass' => [
            'stub' => 'assets/sass/app',
            'path' => 'assets',
            'name' => 'sass/app.scss'
        ],
        'route' => [
            'stub' => 'routes/web',
            'path' => 'routes',
            'name' => 'web.php',
            'replacements' => ['LOWER_NAME', 'STUDLY_NAME']
        ],
        'api' =>  [
            'stub' => 'routes/api',
            'path' => 'routes',
            'name' => 'api.php',
            'replacements' => ['LOWER_NAME']
        ],
        'admin' =>  [
            'stub' => 'routes/admin',
            'path' => 'routes',
            'name' => 'admin.php',
            'replacements' => ['LOWER_NAME']
        ],
        'provider-base' => [
            'path' => 'src',
            'name' => '$STUDLY_NAME$ServiceProvider.php',
            'replacements' => ['LOWER_NAME', 'NAMESPACE', 'STUDLY_NAME']
        ],
        'provider-base-theme' => [
            'path' => 'src',
            'name' => '$STUDLY_NAME$ServiceProvider.php',
            'replacements' => ['LOWER_NAME', 'NAMESPACE', 'STUDLY_NAME']
        ],
        'gitignore' => [
            'name' => '.gitignore',
        ],
        'composer' => [
            'name' => 'composer.json',
            'doblue' => true,
            'replacements' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'NAMESPACE',
                'BASE_TYPE_LOWER_NAME'
            ]
        ],
        'composer2' => [
            'name' => 'composer.json',
            'doblue' => true,
            'replacements' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'NAMESPACE',
                'BASE_TYPE_LOWER_NAME'
            ]
        ],
        'vite-config' => [
            'name' => 'vite.config.js',
            'replacements' => [
                'LOWER_NAME'
            ]
        ],
        'package' => [
            'name' => 'package.json',
            'replacements' => [
                'LOWER_NAME'
            ]
        ],
        'function' => [
            'name' => 'function.php',
            'replacements' => [
                'BASE_TYPE_LOWER_NAME',
                'STUDLY_NAME'
            ]
        ],
        'json' => [
            'name' => '$BASE_TYPE_LOWER_NAME$.json',
            'doblue' => true,
            'replacements' => [
                'VENDOR',
                'BASE_TYPE_LOWER_NAME',
                'STUDLY_NAME',
                'LOWER_NAME',
                'NAMESPACE',
                'JSON_ID',
                'DESCRIPTION'
            ]
        ],
        'json-theme' => [
            'name' => '$BASE_TYPE_LOWER_NAME$.json',
            'doblue' => true,
            'replacements' => [
                'VENDOR',
                'BASE_TYPE_LOWER_NAME',
                'STUDLY_NAME',
                'LOWER_NAME',
                'NAMESPACE',
                'JSON_ID',
                'DESCRIPTION'
            ]
        ],
        'command' => [
            'path' => 'command',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'COMMAND_NAME',
                'CLASS',
                'NAMESPACE'
            ]
        ],
        'component-class' => [
            'path' => 'component-class',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'LOWER_NAME',
                'CLASS',
                'NAMESPACE',
                'COMPONENT_NAME'
            ]
        ],
        'component-view' => [
            'path' => 'component-view',
            'name' => '$LOWER_CLASS$.blade.php',
            'replacements' => [
                'LOWER_NAME',
                'LOWER_CLASS',
                'NAMESPACE',
                'QUOTE'
            ]
        ],
        'controller-api' => [
            'path' => 'controller',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE'
            ]
        ],
        'controller-plain' => [
            'path' => 'controller',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE'
            ]
        ],

        'controller' => [
            'path' => 'controller',
            'file_prex' => 'Controller',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],

        'event' => [
            'path' => 'event',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],
        'factory' => [
            'path' => 'factory',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
                'MODEL_NAMESPACE'
            ]
        ],
        'job' => [
            'path' => 'jobs',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],
        'job-queued' => [
            'path' => 'job',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],

        'model' => [
            'path' => 'model',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
                'NAMESPACE_FACTORY',
                'FILLABLE'
            ]
        ],

        'listener' => [
            'path' => 'listener',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],

        'listener-queued' => [
            'path' => 'listener',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],

        'listener-queued-duck' => [
            'path' => 'listener',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],

        'listener-duck' => [
            'path' => 'listener',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME'
            ]
        ],
        'mail' => [
            'path' => 'emails',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'middleware' => [
            'path' => 'middleware',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],

        'notification' => [
            'path' => 'notifications',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'policy-plain' => [
            'path' => 'policies',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'provider' => [
            'path' => 'provider',
            'file_prex' => 'ServiceProvider',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'route-provider' => [
            'path' => 'provider',
            'file_prex' => 'ServiceProvider',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'request' => [
            'path' => 'request',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'resource' => [
            'path' => 'resource',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'resource-collection' => [
            'path' => 'resource',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'rule' => [
            'path' => 'rules',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'seeder' => [
            'path' => 'seeder',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'action' => [
            'path' => 'action',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'page' => [
            'path' => 'page',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'widget' => [
            'path' => 'widget',
            'name' => '$CLASS_FILE$.php',
            'replacements' => [
                'CLASS',
                'NAMESPACE',
                'LOWER_NAME',
            ]
        ],
        'migration-create' => [
            'stub' => 'migration/create',
            'path' => 'migration',
            'name' => '$FILE_MIGRATION$.php',
            'replacements' => [
                'TABLE',
                'FILE_MIGRATION',
                'FIELDS'
            ]
        ],
        'migration-add' => [
            'stub' => 'migration/add',
            'path' => 'migration',
            'name' => '$FILE_MIGRATION$.php',
            'replacements' => [
                'TABLE',
                'FILE_MIGRATION',
                'FIELDS_UP',
                'FIELDS_DOWN'
            ]
        ],
        'migration-delete' => [
            'stub' => 'migration/delete',
            'path' => 'migration',
            'name' => '$FILE_MIGRATION$.php',
            'replacements' => [
                'TABLE',
                'FILE_MIGRATION',
                'FIELDS_UP',
                'FIELDS_DOWN'
            ]
        ],
        'migration-drop' => [
            'stub' => 'migration/drop',
            'path' => 'migration',
            'name' => '$FILE_MIGRATION$.php',
            'replacements' => [
                'TABLE',
                'FILE_MIGRATION',
                'FIELDS_UP',
                'FIELDS_DOWN'
            ]
        ],
        'migration' => [
            'stub' => 'migration/plain',
            'path' => 'migration',
            'name' => '$FILE_MIGRATION$.php',
            'replacements' => [
                'FILE_MIGRATION',
            ]
        ],
        'helpers-constraint' => [
            'stub' => 'php',
            'path' => 'helpers',
            'name' => 'constraint.php'
        ],
        'helpers-function' => [
            'stub' => 'php',
            'path' => 'helpers',
            'name' => 'function.php'
        ],
    ],
    'gitkeep' => true,
    'files' => [
        'common' => [
            'index-html',
            'config',
            'view',
            'app-js',
            'app-sass',
            'vite-config',
            'package',
            'gitignore',
            'helpers-constraint',
            'helpers-function',
            'option'
        ],
        'module' => [
            'route',
            'api',
            'admin',
            'composer',
            'provider-base',
            'json'
        ],
        'theme' => [
            'layout',
            'layout-none',
            'layout-default',
            'share-header',
            'share-footer',
            'composer',
            'provider-base-theme',
            'json-theme'
        ],
        'plugin' => [
            'composer',
            'provider-base',
            'json'
        ]
    ],
];
