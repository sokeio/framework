<?php

namespace Sokeio\Platform\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Platform\IPipeLoader;
use Sokeio\Platform\ItemInfo;

class PageLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::runLoader(
            $item,
            $item->getPackage()->basePath('Pages'),
            $item->namespace . '\\Pages',
            $item->getPackage()->shortName() . '::pages'
        );
        return $next($item);
    }
}
