<?php

namespace SokeioTheme\Admin\Widget;

use Sokeio\Dashboard\Common\LabelWidget;
use Sokeio\Dashboard\WidgetInfo;


#[WidgetInfo(name: "User count")]
class UserCount extends LabelWidget
{
    public function getWidgetLabel()
    {
        return 'User';
    }
    public function getWidgetValue()
    {
        return (config('sokeio.model.user'))::query()->count();
    }
}
