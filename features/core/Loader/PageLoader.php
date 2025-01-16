<?php

namespace Sokeio\Core\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

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
