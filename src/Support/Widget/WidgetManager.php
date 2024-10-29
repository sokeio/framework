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
            'name' => $info->name,
            'icon' => $info->icon,
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
    private $widgetSettings = [
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-1',
            'group' => 'top',
            'column' => 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
            'params' => [
                'model' => null,
                'title' => 'Data 1'
            ]
        ],
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-1',
            'group' => 'top',
            'column' => 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
            'params' => [
                'model' => null,
                'title' => 'Data 2'
            ]
        ],
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-2',
            'group' => 'top',
            'column' => 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
            'params' => [
                'model' => null,
                'title' => 'Data 3'
            ]
        ]
    ];
    public function getListWidgets()
    {
        return $this->widgetSettings;
    }
}
