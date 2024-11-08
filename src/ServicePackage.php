<?php

namespace Sokeio;

use Illuminate\Support\Str;

class ServicePackage
{
    public string $name;

    public array $configFileNames = [];

    public bool $hasViews = false;

    public bool $hasRouteWeb = false;

    public bool $hasRouteApi = false;

    public bool $hasHelpers = false;

    public string $pathHelper = '';

    public ?string $viewNamespace = null;

    public bool $hasTranslations = false;

    public bool $hasAssets = false;

    public bool $runsMigrations = false;

    public bool $runsSeeds = false;

    public array $migrationFileNames = [];

    public array $commands = [];

    public array $viewComponents = [];

    public array $sharedViewData = [];

    public array $viewComposers = [];

    public string $basePath;

    public ?string $publishableProviderName = null;

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function hasConfigFile($configFileName = null): self
    {
        $configFileName = $configFileName ?? $this->shortName();

        if (!is_array($configFileName)) {
            $configFileName = [$configFileName];
        }

        $this->configFileNames = $configFileName;

        return $this;
    }

    public function publishesServiceProvider(string $providerName): self
    {
        $this->publishableProviderName = $providerName;

        return $this;
    }
    public function shortName(): string
    {
        return Str::after($this->name, 'laravel-');
    }
    public function routeWeb(bool $hasRouteWeb = true): self
    {
        $this->hasRouteWeb = $hasRouteWeb;

        return $this;
    }
    public function routeApi(bool $hasRouteApi = true): self
    {
        $this->hasRouteApi = $hasRouteApi;

        return $this;
    }
    public function hasViews(string $namespace = null): self
    {
        $this->hasViews = true;

        $this->viewNamespace = $namespace;

        return $this;
    }

    public function hasHelpers(string $helper = '/../helpers/'): self
    {
        $this->hasHelpers = true;

        $this->pathHelper = $helper;

        return $this;
    }

    public function hasViewComponent(string $prefix, string $viewComponentName): self
    {
        $this->viewComponents[$viewComponentName] = $prefix;

        return $this;
    }

    public function hasViewComponents(string $prefix, ...$viewComponentNames): self
    {
        foreach ($viewComponentNames as $componentName) {
            $this->viewComponents[$componentName] = $prefix;
        }

        return $this;
    }

    public function sharesDataWithAllViews(string $name, $value): self
    {
        $this->sharedViewData[$name] = $value;

        return $this;
    }

    public function hasViewComposer($view, $viewComposer): self
    {
        if (!is_array($view)) {
            $view = [$view];
        }

        foreach ($view as $viewName) {
            $this->viewComposers[$viewName] = $viewComposer;
        }

        return $this;
    }

    public function hasTranslations(): self
    {
        $this->hasTranslations = true;

        return $this;
    }

    public function hasAssets(): self
    {
        $this->hasAssets = true;

        return $this;
    }

    public function runsMigrations(bool $runsMigrations = true): self
    {
        $this->runsMigrations = $runsMigrations;

        return $this;
    }

    public function runsSeeds(bool $runsSeeds = true): self
    {
        $this->runsSeeds = $runsSeeds;

        return $this;
    }
    public function hasMigration(string $migrationFileName): self
    {
        $this->migrationFileNames[] = $migrationFileName;

        return $this;
    }

    public function hasMigrations(...$migrationFileNames): self
    {
        $this->migrationFileNames = array_merge(
            $this->migrationFileNames,
            collect($migrationFileNames)->flatten()->toArray()
        );

        return $this;
    }

    public function hasCommand(string $commandClassName): self
    {
        $this->commands[] = $commandClassName;

        return $this;
    }

    public function hasCommands(...$commandClassNames): self
    {
        $this->commands = array_merge($this->commands, collect($commandClassNames)->flatten()->toArray());

        return $this;
    }
    public function basePath(string $directory = null): string
    {
        if ($directory === null) {
            return $this->basePath;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . ltrim($directory, DIRECTORY_SEPARATOR);
    }

    public function viewNamespace(): string
    {
        return $this->viewNamespace ?? $this->shortName();
    }

    public function setBasePath(string $path): self
    {
        $this->basePath = $path;

        return $this;
    }
}
