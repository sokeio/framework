<?php

namespace Sokeio;

use Sokeio\Support\Livewire\Concerns\WithLivewirePage;
use Sokeio\Support\Livewire\PageConfig;
use Sokeio\Support\Platform\ItemInfo;

class Page extends Component implements IPage
{
    use WithLivewirePage;
    public static function runLoad(ItemInfo $itemInfo)
    {
        PageConfig::setupRoute(
            static::class,
            $itemInfo->namespace,
            str($itemInfo->getPackage()->shortName())->replace('\\', '.')
        );
    }
}
