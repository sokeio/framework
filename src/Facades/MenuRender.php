<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void renderCallback($callback, $_position = '')
 * @method static void renderItemCallback($callback, $_position = '')
 * @method static void DoRender($item, $_position = '')
 * @method static void DoRenderItem($item, $_position = '')
 * @method static void RegisterType($type, $title, $setting, $renderComponentOrCallback)
 * @method static mixed renderMenuSetting($item)
 * @method static mixed getMenuType()
 *
 * @see \Sokeio\Menu\MenuRenderManager
 */
class MenuRender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Menu\MenuRenderManager::class;
    }
}
