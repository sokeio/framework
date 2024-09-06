<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sokeio\Livewire\Component;
use Sokeio\Livewire\Concerns\WithLivewirePage;
use Sokeio\Menu\MenuItem;
use Sokeio\Menu\MenuManager;
use Sokeio\Platform\ItemInfo;

class Page extends Component implements IPage
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
            ->shortName() . '-page.' . str($url)->replace('\\', '.')->kebab());

        if (static::pageAdmin()) {
            if (static::pageAuth()) {
                $menuTitle = static::menuTitle() ?? str(str($nameRoute)->afterLast('.'))->replace('-', ' ');

                MenuManager::registerItem(
                    MenuItem::make($nameRoute, str($menuTitle)->title()->replace('-', ' '), null)
                        ->route('admin.' . $nameRoute)
                        ->setup(function (MenuItem $item) use ($nameRoute) {
                            if (count(str($nameRoute)->split('/\./', -1, PREG_SPLIT_NO_EMPTY)) > 2) {
                                $item->target = str($nameRoute)->beforeLast('.');
                            }
                        })
                );
            }

            Platform::routeAdmin(function () use ($classMe, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $classMe)->name($nameRoute);
            }, !static::pageAuth());
        } else {
            Platform::routeWeb(function () use ($classMe, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $classMe)->name($nameRoute);
            }, static::pageAuth());
        }
    }
}
