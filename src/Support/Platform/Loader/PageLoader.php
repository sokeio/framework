<?php

namespace Sokeio\Support\Platform\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

class PageLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::runLoader(
            $item,
            $item->getPackage()->basePath('Page'),
            $item->namespace . '\\Page',
            $item->getPackage()->shortName() . '::page'
        );
        return $next($item);
    }
}
