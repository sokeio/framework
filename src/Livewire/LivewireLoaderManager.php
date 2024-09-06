<?php

namespace Sokeio\Livewire;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class LivewireLoaderManager
{
    public function __construct(protected $app = null) {}
    private $arrComponent = null;
    public function getComponents()
    {
        return $this->arrComponent;
    }
    private function pushComponent($component, $class)
    {
        if (!$this->arrComponent) {
            $this->arrComponent = [];
        }
        if ($class && !isset($this->arrComponent[$component])) {
            Livewire::component($component, $class);
            $this->arrComponent[$component] = $class;
        } else {
            Log::info('Register component is exits.' . $component . ':' . $class);
        }
    }
    private function getNameByClass($class)
    {
        return  trim(Str::of($class)
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.'), '.');
    }
    public function register($class, $namespace, $aliasPrefix = '')
    {
        $alias = $aliasPrefix . Str::of($class)
            ->after($namespace . '\\')
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.');
        // fix class namespace
        $alias_class = $this->getNameByClass($class);
        if (Str::endsWith($class, ['\Index', '\index'])) {
            $this->pushComponent(Str::beforeLast($alias, '.index'), $class);
            $this->pushComponent(Str::beforeLast($alias_class, '.index'), $class);
        }
        $this->pushComponent($alias, $class);
        $this->pushComponent($alias_class, $class);
        return true;
    }
}
