<?php

namespace Sokeio\Core\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

class LivewireLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::runLoader(
            $item,
            $item->getPackage()->basePath('Livewire'),
            $item->namespace . '\\Livewire',
            $item->getPackage()->shortName() . '::'
        );
        return $next($item);
    }
}
