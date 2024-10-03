<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sokeio\Support\Livewire\Concerns\WithLivewirePage;
use Sokeio\Support\Menu\MenuItem;
use Sokeio\Support\Menu\MenuManager;
use Sokeio\Support\Platform\ItemInfo;

class Page extends Component implements IPage
{
    use WithLivewirePage;
    public static function runLoad(ItemInfo $itemInfo)
    {
        $classMe = static::class;
        $namespacePage = $itemInfo->namespace . '\\Page\\';
        // do nothing
        $url = str($classMe)->after($namespacePage);
        $urlRoute = static::pageUrl() ?? str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        $nameRoute = static::pageName() ??  ($itemInfo
            ->getPackage()
            ->shortName() . '-page.' . str($url)->split('/\\\\/', -1)->map([Str::class, 'kebab'])->join('.'));

        if (static::pageAdmin()) {
            if (static::pageAuth() && static::menuEnabled()) {
                $menuTitle = static::menuTitle() ?? str(str($nameRoute)->afterLast('.'))->replace('-', ' ');
                $target = static::menuTarget();
                if (!$target && count(str($nameRoute)->split('/\./', -1, PREG_SPLIT_NO_EMPTY)) > 2) {
                    $target = str($nameRoute)->beforeLast('.');
                }
                MenuManager::registerItem(
                    MenuItem::make($nameRoute, str($menuTitle)->title()->replace('-', ' '), null)
                        ->route('admin.' . $nameRoute)
                        ->setup(function (MenuItem $item) use ($target) {
                            if ($target) {
                                $item->target =  $target;
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
