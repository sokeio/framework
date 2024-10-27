<?php

namespace Sokeio\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Sokeio\Exceptions\InvalidPackage;
use Sokeio\Platform;
use Sokeio\Support\Platform\ItemInfo;
use Sokeio\ServicePackage;
use ReflectionClass;
use Sokeio\Theme;

trait WithServiceProvider
{
    public ServicePackage $package;
    abstract public function configurePackage(ServicePackage $package): void;
    private $extendPackage = false;
    private static ItemInfo $itemInfo;
    public static function itemInfo(): ItemInfo
    {
        return static::$itemInfo;
    }
    protected function extendPackage($flg = true)
    {
        $this->extendPackage = $flg;
    }
    public function register()
    {
        $this->registeringPackage();

        $this->package = $this->newPackage();

        $this->package->setBasePath($this->getPackageBaseDir());

        $this->configurePackage($this->package);
        $this->configurePackaged();
        if (empty($this->package->name)) {
            throw InvalidPackage::nameIsRequired();
        }

        foreach ($this->package->configFileNames as $configFileName) {
            Log::info($this->getPackagePath("/../config/{$configFileName}.php"));
            if (File::exists($this->getPackagePath("/../config/{$configFileName}.php"))) {
                Log::info("Load config file: {$configFileName}.php");
                $this->mergeConfigFrom($this->getPackagePath("/../config/{$configFileName}.php"), $configFileName);
            }
        }
        $fileConfig = $this->getPackagePath("/../config/{$this->package->shortName()}.php");
        if (File::exists($fileConfig)) {
            $this->mergeConfigFrom($fileConfig, $this->package->shortName());
        }
        self::$itemInfo = Platform::loadFromServicePackage($this->package);
        if ((static::itemInfo()->isActive() || static::itemInfo()->isVendor()) && $this->package->hasRouteWeb) {
            Route::middleware('web')
                ->group($this->getPackagePath('/../routes/web.php'));
        }

        if (!$this->extendPackage) {
            $this->packageRegistered();
        }
        if (static::itemInfo()->getManager()->isTheme()) {
            $this->package->name(Theme::getNamespace(Arr::get(static::itemInfo(), 'admin') === true));
        } else {
            Platform::theme()->loadFromPath($this->getPackagePath('/..'));
        }
        return $this;
    }

    public function newPackage(): ServicePackage
    {
        return new ServicePackage();
    }
    private function publishesVerdor($name, $source, $target)
    {
        if ($this->checkPackageExists($source)) {
            $this->publishes([
                $this->getPackagePath($source) => $target,
            ], "{$this->package->shortName()}-{$name}");
        }
    }
    private function checkPackageExists($path)
    {
        return File::exists($this->getPackagePath($path));
    }
    protected function getPackagePath($path)
    {
        return $this->package->basePath($path);
    }
    private const BASE_VIEW_PATH = "/../resources/views";
    protected function loadMigrationInPackages()
    {
        if (!$this->package->runsMigrations) {
            return;
        }

        if ($this->checkPackageExists("/../database/migrations/")) {
            $migrationFiles =  File::allFiles($this->getPackagePath("/../database/migrations/"));
            if ($migrationFiles && count($migrationFiles) > 0) {
                foreach ($migrationFiles  as $file) {
                    if ($file->getExtension() == "php") {
                        $this->loadMigrationsFrom($file->getRealPath());
                    }
                }
            }
        }
    }
    protected function bootMigrations()
    {
        if (!$this->checkPackageExists("/../database/migrations")) {
            return;
        }
        $now = Carbon::now();
        foreach ($this->package->migrationFileNames as $migrationFileName) {
            $filePath = $this->getPackagePath("/../database/migrations/{$migrationFileName}.php");
            if (!file_exists($filePath)) {
                // Support for the .stub file extension
                $filePath .= '.stub';
            }

            $this->publishes([
                $filePath => $this->generateMigrationName(
                    $migrationFileName,
                    $now->addSecond()
                ),
            ], "{$this->package->shortName()}-migrations");

            if ($this->package->runsMigrations) {
                $this->loadMigrationsFrom($filePath);
            }
        }
        $this->loadMigrationInPackages();
    }
    protected function loadResourceInPackages($langPath)
    {
        if ($this->package->hasTranslations && File::exists($this->getPackagePath('/../resources/lang'))) {
            $this->loadTranslationsFrom(
                $this->getPackagePath('/../resources/lang/'),
                $this->package->shortName()
            );

            $this->loadJsonTranslationsFrom($this->getPackagePath('/../resources/lang'));

            $this->loadJsonTranslationsFrom($langPath);
        }

        if ($this->package->hasViews && File::exists($this->getPackagePath(self::BASE_VIEW_PATH))) {
            $this->loadViewsFrom($this->getPackagePath(self::BASE_VIEW_PATH), $this->package->viewNamespace());
        }
        if ($this->package->viewComponents && File::exists($this->getPackagePath('/../Components'))) {
            foreach ($this->package->viewComponents as $componentClass => $prefix) {
                $this->loadViewComponentsAs($prefix, [$componentClass]);
            }

            if (count($this->package->viewComponents)) {
                $path = base_path("app/View/Components/vendor/{$this->package->shortName()}");
                $this->publishes([
                    $this->getPackagePath('/../Components') => $path,
                ], "{$this->package->name}-components");
            }
        }
    }
    public function boot()
    {
        $this->bootingPackage();

        if ($this->package->hasTranslations) {
            $langPath = 'vendor/' . $this->package->shortName();

            $langPath = (function_exists('lang_path'))
                ? lang_path($langPath)
                : resource_path('lang/' . $langPath);
        }

        if ($this->app->runningInConsole() || env('SOKEIO_SETUP', false)) {
            $this->bootMigrations();
            foreach ($this->package->configFileNames as $config) {
                $this->publishesVerdor('config', "/../config/{$config}.php", config_path("{$config}.php"));
            }

            if ($this->package->hasViews) {
                $targetPath = base_path("resources/views/vendor/{$this->package->shortName()}");
                $this->publishesVerdor('views', self::BASE_VIEW_PATH, $targetPath);
            }
            if ($this->package->runsSeeds && $this->checkPackageExists("/../database/seeders")) {
                $seedFiles =  File::allFiles($this->getPackagePath("/../database/seeders/"));
                if ($seedFiles && count($seedFiles) > 0) {
                    foreach ($seedFiles  as $file) {
                        if ($file->getExtension() == "php") {
                            includeFile($file->getRealPath());
                        }
                    }
                }
            }
            if ($this->package->hasTranslations) {
                $this->publishesVerdor('translations', "/../resources/lang/", $langPath);
            }

            if ($this->package->hasAssets) {
                $pathVendor = public_path("vendor/{$this->package->shortName()}");
                $this->publishesVerdor('assets', "/../resources/dist", $pathVendor);
            }
        }

        if (!empty($this->package->commands)) {
            $this->commands($this->package->commands);
        }
        if (($commands = config($this->package->shortName() . '.commands'))
            && is_array($commands) && !empty($commands)
        ) {
            $this->commands($commands);
        }
        $this->loadResourceInPackages($langPath);
        foreach ($this->package->sharedViewData as $name => $value) {
            View::share($name, $value);
        }

        foreach ($this->package->viewComposers as $viewName => $viewComposer) {
            View::composer($viewName, $viewComposer);
        }

        if (!$this->extendPackage) {
            $this->packageBooted();
        }

        return $this;
    }

    public static function generateMigrationName(string $migrationFileName, Carbon $now): string
    {
        $migrationsPath = 'migrations/';

        $len = strlen($migrationFileName) + 4;

        if (Str::contains($migrationFileName, '/')) {
            $migrationsPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
        }

        foreach (glob(database_path("{$migrationsPath}*.php")) as $filename) {
            if (substr($filename, -$len) === $migrationFileName . '.php') {
                return $filename;
            }
        }
        $file_name = $now->format('Y_m_d_His') . '_' . Str::of($migrationFileName)->snake()->finish('.php');
        return database_path($migrationsPath . $file_name);
    }

    public function registeringPackage()
    {
        //Do nothing
    }
    public function configurePackaged()
    {
        //Do nothing
    }
    public function packageRegistered()
    {
        //Do nothing
    }

    public function bootingPackage()
    {
        //Do nothing
    }

    public function packageBooted()
    {
        //Do nothing
    }

    protected function getPackageBaseDir(): string
    {
        $reflector = new ReflectionClass(get_class($this));
        return dirname($reflector->getFileName());
    }

    public function getNamespaceName(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return $reflector->getNamespaceName();
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
