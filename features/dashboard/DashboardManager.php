<?php

namespace Sokeio\Dashboard;


class DashboardManager
{
    private $widgets = [];
    public function registerClass($widget)
    {
        $info = WidgetInfo::fromClass($widget);
        if (!$info) {
            return;
        }
        $key = md5($widget);
        if (isset($this->widgets[$key])) {
            return; // already registered
        }
        $this->widgets[$key] = [
            'class' => $widget,
            'key' => $key,
            'info' => $info,
        ];
    }

    public function getWidgets()
    {
        return $this->widgets;
    }
    public function getWidget($key)
    {
        return $this->widgets[$key] ?? null;
    }
    public function getDashboard($key = 'default')
    {
        return  collect($this->widgets)->where(function ($value) use ($key) {
            return $value['info']->dasboard === $key;
        });
    }
}
