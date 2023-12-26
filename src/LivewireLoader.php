<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;

class LivewireLoader
{
    private static $arrComponent = null;
    public static function getComponents()
    {
        return self::$arrComponent;
    }
    private static function pushComponent($component,$class)
    {
        if (!self::$arrComponent) self::$arrComponent = collect([]);
        self::$arrComponent->push($component);
        if($class){
              Livewire::component($component, $class);
        }
    }
    public static function getNameByClass($class)
    {
        return  trim(Str::of($class)
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.'), '.');
    }
    public static function getNameComponent($name)
    {
        return isset(self::$arrComponent[$name]) ? self::getNameByClass(self::$arrComponent[$name]) : self::getNameByClass($name);
    }
    public static function Register($path, $namespace, $aliasPrefix = '')
    {
        AllClass(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix) {
                $alias = $aliasPrefix . Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');
                // fix class namespace
                $alias_class = self::getNameByClass($class);
                if (Str::endsWith($class, ['\Index', '\index'])) {
                    self::pushComponent(Str::beforeLast($alias, '.index'), $class);
                    self::pushComponent(Str::beforeLast($alias_class, '.index'), $class);
                }
                self::pushComponent($alias,$class);
                self::pushComponent($alias_class,$class);
            },
            function ($class) {
                if (!class_exists($class)) return false;
                $refClass = new ReflectionClass($class);
                return  $refClass && !$refClass->isAbstract()  && $refClass->isSubclassOf(Component::class);
            }
        );
    }
    public static function ViewRoute($class)
    {
        return function () use ($class) {
            return (new $class)(app(), Route::current());
        };
    }
}
