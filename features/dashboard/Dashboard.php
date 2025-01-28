<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Facades\Facade;
use Sokeio\Dashboard\DashboardManager;

/**
 * @see DashboardManager
 *
 * @method static DashboardManager registerClass($widget)
 * @method static mixed getDashboard($key = 'default')
 * @method static mixed getWidget($key)
 * @method static void setDataDashboard($key,$data);
 * @method static mixed settingWidgets($key = null, $value = null, $dashboard = 'default')
 */
class Dashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_dashboard';
    }
}
