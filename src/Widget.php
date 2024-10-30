<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Support\Widget\WidgetManager;
use Sokeio\Support\Widget\WidgetSetting;

/**
 * @see WidgetManager
 *
 * @method static WidgetManager registerClass($widget)
 * @method static array getListWidgets()
 * @method static array getWidget($key)
 * @method static string getColumnClass($column)
 * @method static WidgetSetting getWidgetById()
 */
class Widget extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_widget';
    }
}
