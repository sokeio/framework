<?php

namespace Sokeio\Core\Concerns;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

trait WithLivewireComponent
{
    private $arrLivewireComponent = null;

    private function pushLivewireComponent($component, $class)
    {
        if (!$this->arrLivewireComponent) {
            $this->arrLivewireComponent = [];
        }
        if ($class && !isset($this->arrLivewireComponent[$component])) {
            Livewire::component($component, $class);
            $this->arrLivewireComponent[$component] = $class;
        } else {
            Log::info('Register component is exits.' . $component . ':' . $class);
        }
    }
    private function getLivewireNameByClass($class)
    {
        return  trim(Str::of($class)
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.'), '.');
    }
    public function getLivewireComponents()
    {
        return $this->arrLivewireComponent;
    }
    public function registerLivewire($class, $namespace, $aliasPrefix = '')
    {
        $alias = $aliasPrefix . Str::of($class)
            ->after($namespace . '\\')
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.');
        $alias = str($alias)->replace('::.', '::')->toString();
        // fix class namespace
        $alias_class = $this->getLivewireNameByClass($class);
        if (Str::endsWith($class, ['\Index', '\index'])) {
            $this->pushLivewireComponent(Str::beforeLast($alias, '.index'), $class);
            $this->pushLivewireComponent(Str::beforeLast($alias_class, '.index'), $class);
        }
        $this->pushLivewireComponent($alias, $class);
        $this->pushLivewireComponent($alias_class, $class);
        return true;
    }
}
