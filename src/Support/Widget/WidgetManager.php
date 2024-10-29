<?php

namespace Sokeio\Support\Widget;


class WidgetManager
{
    private $widgets = [];
    public function registerClass($widget)
    {
        $info = WidgetInfo::getWidgetInfoFromUI($widget);
        if (!$info) {
            return;
        }
        $key = $info->key;
        if (isset($this->widgets[$key])) {
            return; // already registered
        }
        $this->widgets[$key] = [
            'key' => $key,
            'class' => $widget,
            'info' => $info,
            'icon' => $info->icon,
        ];
    }
}
