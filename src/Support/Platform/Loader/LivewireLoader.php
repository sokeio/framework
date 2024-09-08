<?php

namespace Sokeio\Support\Platform\Loader;

use Closure;
use Sokeio\Platform;
use Sokeio\Support\Platform\IPipeLoader;
use Sokeio\Support\Platform\ItemInfo;

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
