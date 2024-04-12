<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;
use Sokeio\Dashboard\Widget;

/**
 * 
 * @method static void Register($key, $widget = null)
 * @method static mix getWidget()
 * @method static Widget getWidgetByKey($key)
 *
 * @see \Sokeio\Dashboard\DashboardManager
 */
class Dashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Dashboard\DashboardManager::class;
    }
}
