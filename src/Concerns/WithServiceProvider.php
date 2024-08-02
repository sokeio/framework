<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Str;
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
    public $baseType = '';
    public  $dataInfo;
    protected function registerModels(array $models): void
    {
        foreach ($models as $service => $class) {
            $model = $this->app['config'][Str::replaceLast('.', '.models.', $service)];
            $this->app->singletonIf($service, $model);
            $model === $class || $this->app->alias($service, $class);
            $this->app->singletonIf($model, $model);
        }
    }
    public function register()
    {
        $this->extendPackage();
        $this->registerBase();

        $this->packageRegistered();
        if ($info = Platform::getDataInfo($this->package->basePath('/../'))) {
            $this->baseType = $info['baseType'];
            $this->dataInfo = $info['dataInfo'];
        }

        if ($this->baseType == 'theme') {
            $this->package->name('theme');
        }

        return $this;
    }


    public function boot()
    {
        if ($this->baseType == 'module') {
            Theme::Load($this->package->basePath('/../themes/'));
            Plugin::Load($this->package->basePath('/../plugins/'));
        }
        if ($this->baseType != 'theme') {
            RouteEx::Load($this->package->basePath('/../routes/'));
        } else {
            $this->package->hasAssets = false;
        }
        if ($this->baseType && $this->dataInfo) {
            Assets::AssetType($this->dataInfo['name'], $this->baseType);
        }
        $this->bootBase();
        $namespace = $this->getNamespaceName();
        $shortname = $this->package->shortName();
        LivewireLoader::Register($this->package->basePath('/Livewire'), $namespace . '\\Livewire', $shortname . '::');
        LivewireLoader::registerPage($this->package->basePath('/Pages'), $namespace . '\\Pages', $shortname . '::page.');
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
