<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\Component;
use Sokeio\Livewire\Concerns\WithLivewirePage;
use Sokeio\Platform\ItemInfo;

class Page extends Component implements ILoader
{
    use WithLivewirePage;
    public static function runLoad(ItemInfo $itemInfo)
    {
        $classMe = static::class;
        $namespacePage = $itemInfo->namespace . '\\Page';
        // do nothing
        $url = str($classMe)->after($namespacePage . '\\');
        $url = str($url)->before('.php');
        $urlRoute = str($url)->replace('\\', '/')->kebab();
        $nameRoute = str($url)->replace('\\', '.')->kebab();
        
        Route::get($urlRoute, $classMe)->name($nameRoute);
    }
}
