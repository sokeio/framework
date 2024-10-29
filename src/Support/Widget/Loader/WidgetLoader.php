<?php

namespace Sokeio\Support\Widget\Loader;

use Closure;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

class WidgetLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        // Platform::runLoader(
        //     $item,
        //     $item->getPackage()->basePath('Page'),
        //     $item->namespace . '\\Page',
        //     $item->getPackage()->shortName() . '::page'
        // );
        return $next($item);
    }
}
