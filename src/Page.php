<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sokeio\Livewire\Component;
use Sokeio\Livewire\Concerns\WithLivewirePage;
use Sokeio\Platform\ItemInfo;

class Page extends Component implements ILoader
{
    use WithLivewirePage;
    public static function runLoad(ItemInfo $itemInfo)
    {
        $classMe = static::class;
        $namespacePage = $itemInfo->namespace . '\\Pages\\';
        // do nothing
        $url = str($classMe)->after($namespacePage);
        $urlRoute = str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        $nameRoute =  $itemInfo->getPackage()->shortName() . '-page.' . str($urlRoute)->replace('/', '.');
        // echo '<pre>';
        // print_r([
        //     'url' => $url,
        //     'urlRoute' => $urlRoute,
        //     'nameRoute' => $nameRoute
        // ]);
        if (static::isThemeAdmin()) {
        } else {
            Route::get($urlRoute, $classMe)->name($nameRoute);
        }
    }
}
