<?php

namespace Sokeio\Support\Platform\Loader;

use Closure;
use Sokeio\Attribute\Route;
use Sokeio\Platform;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

class ControllerLoader implements IPipeLoader
{
    const KEY = 'Controller';
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::scanAllClass(
            $item->getPackage()->basePath('Http/Controllers'),
            $item->namespace . '\\Http\\Controllers',
            function ($class) use ($item) {
                if (!str($class)->endsWith(self::KEY)) {
                    return;
                }
                Route::register($class, $item);
            }
        );
        return $next($item);
    }
}
