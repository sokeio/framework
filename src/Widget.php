<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Support\Widget\WidgetManager;

/**
 * @see WidgetManager
 *
 * @method static WidgetManager registerClass()
 * @method static array getListWidgets()
 * @method static array getWidget($key)
 */
class Widget extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_widget';
    }
}
