<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Widget\WidgetManager;
use Sokeio\Widget\WidgetSetting;

/**
 * @see WidgetManager
 *
 * @method static WidgetManager registerClass($widget)
 * @method static array getListWidgets()
 * @method static array getWidgets()
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
