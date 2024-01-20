<?php

namespace Sokeio\Concerns;

use Sokeio\Facades\Action;
use Sokeio\Laravel\WithServiceProvider as WithServiceProviderBase;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\LivewireLoader;
use Sokeio\RouteEx;

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
