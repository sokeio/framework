<?php

namespace Sokeio\Dashboard;

class DashboardManager
{
    private $widgets = [];
    private $temps = null;
    public function register($key, $widget = null)
    {
        $this->temps = null;
        if ($widget == null) {
            $this->widgets[md5($key)] = $key;
        } else {
            $this->widgets[md5($key)] = $widget;
        }
    }
    public function getWidget()
    {
        if ($this->temps != null) return $this->temps;

        $temps = [];
        foreach ($this->widgets as $key => $widget) {
            if (is_string($widget)) {
                $temps[$key] = app($widget, ['key' => $key]);
            } elseif (is_callable($widget)) {
                $inst = new Widget($key);
                $widget($inst);
            } else {
                $temps[$key] = $widget;
            }
        }
        return $this->temps = $temps;
    }
    public function getWidgetByKey($key)
    {
        $data = $this->getWidget();
        if (isset($data[$key])) return $data[$key];
        return null;
    }
}
