<?php

namespace Sokeio\Livewire;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Finder\SplFileInfo;
use Livewire\Livewire;
use Livewire\Component as LivewireComponent;
use ReflectionClass;

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
    protected function getNameByClass($class)
    {
        return  trim(Str::of($class)
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.'), '.');
    }
    protected function scanAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
    {
        if (!is_dir($directory)) {
            return [];
        }
        $classList = collect(File::allFiles($directory))
            ->map(function (SplFileInfo $file) use ($namespace) {
                return (string) Str::of($namespace)
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(function (string $class) {
                return class_exists($class);
            });

        if ($callback) {
            if ($filter) {
                $classList = $classList->filter($filter);
            }
            $classList->each($callback);
        } else {
            return $classList->all();
        }
    }
    public function register($path, $namespace, $aliasPrefix = '', $callback = null)
    {
        $this->scanAllClass(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix, $callback) {
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
                if ($callback) {
                    $callback($class);
                }
            },
            function ($class) {
                if (!class_exists($class)) {
                    return false;
                }
                $refClass = new ReflectionClass($class);
                return  $refClass && !$refClass->isAbstract()  && $refClass->isSubclassOf(LivewireComponent::class);
            }
        );
    }
}
