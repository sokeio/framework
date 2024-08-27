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
        $urlRoute = static::pageUrl() ?? str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        $nameRoute = static::pageName() ??  ($itemInfo
            ->getPackage()
            ->shortName() . '-page.' . str($urlRoute)->replace('/', '.'));

        if (static::isThemeAdmin()) {
            Platform::routeAdmin(function () use ($classMe, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $classMe)->name($nameRoute);
            }, !static::isAuth());
        } else {
            Platform::routeWeb(function () use ($classMe, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $classMe)->name($nameRoute);
            }, static::isAuth());
        }
    }
}
