<?php

namespace Sokeio\Support\Platform\Loader;

use Closure;
use Illuminate\Support\Facades\Blade;
use Sokeio\Platform;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

class ComponentLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::scanAllClass(
            $item->getPackage()->basePath('Components'),
            $item->namespace . '\\Components',
            function ($class) use ($item) {
                Blade::component($class, $item->getPackage()->shortName() . '::component.' . $class);
            }
        );
        return $next($item);
    }
}
