<?php

namespace BytePlatform\Concerns;

use BytePlatform\Action;
use BytePlatform\Dashboard;
use BytePlatform\Laravel\WithServiceProvider as WithServiceProviderBase;
use BytePlatform\Facades\Assets;
use BytePlatform\Facades\Platform;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Shortcode;
use BytePlatform\Facades\Theme;
use BytePlatform\FieldView;
use BytePlatform\LivewireLoader;
use BytePlatform\RouteEx;

trait WithServiceProvider
{
    use WithServiceProviderBase {
        register as protected registerBase;
        boot as protected bootBase;
    }
    public $base_type = '';
    public  $data_info;
    public function configurePackaged()
    {
    }

    public function register()
    {
        $this->ExtendPackage();
        $this->registerBase();



        $this->packageRegistered();

 
        if ($this->base_type != 'theme') {
            if ($shortcodes = config($this->package->shortName() . '.shortcodes')) {
                if (is_array($shortcodes) && count($shortcodes) > 0) {
                    Shortcode::Register($shortcodes, $this->package->shortName());
                }
            }
            if ($actionTypes = config($this->package->shortName() . '.actions')) {
                if (is_array($actionTypes) && count($actionTypes) > 0) {
                    Action::Register($actionTypes, $this->package->shortName());
                }
            }
        }

        return $this;
    }


    public function boot()
    {
        if ($info = Platform::getDataInfo($this->package->basePath('/../'))) {
            $this->base_type = $info['base_type'];
            $this->data_info = $info['data_info'];
        }
        if ($this->base_type == 'module') {
            Theme::Load($this->package->basePath('/../themes/'));
            Plugin::Load($this->package->basePath('/../plugins/'));
        }
        if ($this->base_type != 'theme')
            RouteEx::Load($this->package->basePath('/../routes/'));

        if ($this->base_type && $this->data_info) {
            Assets::AssetType($this->data_info['name'], $this->base_type);
        }
        if ($this->base_type == 'theme') {
            $this->package->name('theme');
        }
        $this->bootBase();
        if ($this->app->runningInConsole()) {
            if ($this->package->runsMigrations) {
                AllFile($this->package->basePath("/../database/migrations/"), function ($file) {
                    $this->loadMigrationsFrom($file->getRealPath());
                }, function ($file) {
                    return $file->getExtension() == "php";
                });
            }

            if ($this->package->runsSeeds) {
                AllFile($this->package->basePath("/../database/seeders/"), function ($file) {
                    include_once($file->getRealPath());
                }, function ($file) {
                    return $file->getExtension() == "php";
                });
            }
        }
        LivewireLoader::Register($this->package->basePath('/Livewire'), $this->getNamespaceName() . '\\Livewire', $this->package->shortName() . '::');

        $this->packageBooted();

        return $this;
    }
    protected function loadViewsFrom($path, $namespace)
    {
        $this->callAfterResolving('view', function ($view) use ($path, $namespace) {
            if (
                isset($this->app->config['view']['paths']) &&
                is_array($this->app->config['view']['paths'])
            ) {
                foreach ($this->app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                        $view->prependNamespace($namespace, $appPath);
                    }
                }
            }

            $view->prependNamespace($namespace, $path);
        });
    }
}
