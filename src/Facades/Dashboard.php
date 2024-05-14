<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static void Register($key, $widget = null)
 * @method static array getPosition()
 * @method static mixed getData()
 * @method static mixed getDashboard($id)
 * @method static mixed getWidgetInDashboard($id)
 * @method static mixed getWidgetComponent($dashboardId, $widgetId, $component)
 * @method static mixed getWidgetClassByKey($key)
 * @method static mixed getWidget($id)
 * @method static mixed getWidgetType()
 *
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
