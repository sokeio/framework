<?php

namespace SokeioTheme\Admin\Widget;

use Sokeio\Dashboard\Common\LabelWidget;
use Sokeio\Dashboard\WidgetInfo;

#[WidgetInfo(name: "Permission count", show: false)]
class PermissionCount extends LabelWidget
{
    public function getWidgetLabel()
    {
        return 'Permission';
    }
    public function getWidgetValue()
    {
        return (config('sokeio.model.permission'))::query()->count();
    }
}
