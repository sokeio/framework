<?php

namespace Sokeio\Widget\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

class WidgetLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::runLoader(
            $item,
            $item->getPackage()->basePath('Widget'),
            $item->namespace . '\\Widget',
            $item->getPackage()->shortName() . '::widget'
        );
        return $next($item);
    }
}
