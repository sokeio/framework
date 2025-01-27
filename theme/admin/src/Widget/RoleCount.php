<?php

namespace SokeioTheme\Admin\Widget;

use Sokeio\Dashboard\Common\LabelWidget;
use Sokeio\Dashboard\WidgetInfo;


#[WidgetInfo()]
class RoleCount extends LabelWidget
{
    public function getWidgetLabel()
    {
        return 'Role';
    }
    public function getWidgetValue()
    {
        return (config('sokeio.model.role'))::query()->count();
    }
}