<?php

namespace Sokeio\Support\Platform\Concerns;

use Sokeio\Platform;

trait WithRegisterItemInfo
{
    private $flgRegister = false;
    private $flgLoader = false;
    private $flgBoot = false;
    private $providers = [];
    private function registerItemInfo($path)
    {
        if ($this->isVendor() && !$this->getManager()->isTheme()) {
            return;
        }
        $this->registerComposer($path);
    }
    private function registerComposer($path)
    {

        if (!file_exists($path . '/composer.json')) {
            return [];
        }

        $composer = json_decode(file_get_contents($path . '/composer.json'), true);
        $loader = new \Composer\Autoload\ClassLoader();
        $psr4 = data_get($composer, 'autoload.psr-4', []);
        foreach ($psr4 as $key => $value) {
            if (file_exists($path . '/' . $value)) {
                $loader->addPsr4($key, $path . '/' . $value, true);
            }
        }
        collect(data_get($composer, 'autoload.files', []))
            ->map(function ($item) use ($path) {
                return $path . '/' . $item;
            })->filter(function ($item) {
                return file_exists($item);
            })->each(function ($path) {
                include_once($path);
            });
        $loader->register(true);
        $providers = data_get($composer, 'extra.laravel.providers', []);
        collect($providers)->map(function ($item) {
            if (!isset($this->providers[$item])) {
                $this->providers[$item] = app()->resolveProvider($item);
                if (property_exists($this->providers[$item], '$package')) {
                    $this->package = $this->providers[$item]->package;
                }
                call_user_func([$this->providers[$item], 'register']);
            }
        });
        return $composer;
    }
    public function register()
    {
        if ($this->flgRegister) {
            return;
        }
        $this->flgRegister = true;
        $this->registerItemInfo($this->path);
    }
    public function boot()
    {
        if ($this->flgBoot) {
            return;
        }
        $this->flgBoot = true;
        if ($this->providers == null) {
            $this->providers = [];
        }
        foreach ($this->providers as $item) {
            $item->boot();
        }
    }
    public function loader(): void
    {
        if ($this->flgLoader) {
            return;
        }
        $this->flgLoader = true;
        Platform::applyLoader($this);
    }
}
