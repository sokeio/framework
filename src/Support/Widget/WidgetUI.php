<?php

namespace Sokeio\Support\Widget;

use Sokeio\UI\BaseUI;

class WidgetUI extends BaseUI
{
    private $widgetInfo;
    public function getDataParam($key, $default = '')
    {
        return $key . '1234';
    }
    public function getWidgetInfo(): WidgetInfo
    {
        return $this->widgetInfo ?? ($this->widgetInfo = WidgetInfo::getWidgetInfoFromUI(self::class));
    }
    public static function paramUI()
    {
        return [];
    }
}
