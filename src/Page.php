<?php

namespace Sokeio;

use Sokeio\Livewire\Concerns\WithLivewirePage;
use Sokeio\Livewire\PageConfig;
use Sokeio\Core\ItemInfo;

class Page extends Component implements ILoader
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
