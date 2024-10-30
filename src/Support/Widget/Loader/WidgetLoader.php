<?php

namespace Sokeio\Support\Widget\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

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
