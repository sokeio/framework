<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Facades\Facade;
use Sokeio\Dashboard\DashboardManager;

/**
 * @see DashboardManager
 *
 * @method static DashboardManager registerClass($widget)
 * @method static mixed getDashboard($key = 'default')
 */
class Dashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_dashboard';
    }
}
