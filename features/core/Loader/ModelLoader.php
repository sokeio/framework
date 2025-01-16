<?php

namespace Sokeio\Core\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

class ModelLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::scanAllClass(
            $item->getPackage()->basePath('Models'),
            $item->namespace . '\\Models',
            function ($class) use ($item) {
                Platform::registerModel($class, $item);
            }
        );
        return $next($item);
    }
}
