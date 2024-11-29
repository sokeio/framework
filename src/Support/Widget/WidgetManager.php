<?php

namespace Sokeio\Support\Widget;


class WidgetManager
{
    private $widgets = [];
    public function registerClass($widget)
    {
        $info = WidgetInfo::getInfoFrom($widget);
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
    public function getColumns()
    {
        return [
            'column1' => 'col-lg-1 col-md-2 col-sm-6 col-xs-12',
            'column2' => 'col-lg-2 col-md-3 col-sm-6 col-xs-12',
            'column3' => 'col-lg-3 col-md-4 col-sm-6 col-xs-12',
            'column4' => 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
            'column5' => 'col-lg-5 col-md-6 col-sm-12 col-xs-12',
            'column6' => 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
            'column7' => 'col-lg-7 col-md-6 col-sm-12 col-xs-12',
            'column8' => 'col-lg-8 col-md-6 col-sm-12 col-xs-12',
            'column9' => 'col-lg-9 col-md-6 col-sm-12 col-xs-12',
            'column10' => 'col-lg-10 col-md-6 col-sm-12 col-xs-12',
            'column11' => 'col-lg-11 col-md-6 col-sm-12 col-xs-12',
            'column12' => 'col-lg-12 col-md-6 col-sm-12 col-xs-12',
        ];
    }
    public function getColumnClass($column)
    {
        $columns = $this->getColumns();
        return isset($columns[$column]) ? $columns[$column] : 'col-lg-4 col-md-12 col-sm-12 col-xs-12';
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
            'column' => 'column3',
            'params' => [
                'model' => null,
                'title' => 'Data 1'
            ]
        ],
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-2',
            'group' => 'top',
            'column' => 'column3',
            'params' => [
                'model' => null,
                'title' => 'Data 2'
            ]
        ],
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-3',
            'group' => 'top',
            'column' => 'column3',
            'params' => [
                'model' => null,
                'title' => 'Data 3'
            ]
        ],
        [
            'key' => 'sokeio:count-model',
            'id' => 'widget-4',
            'group' => 'center',
            'column' => 'column3',
            'params' => [
                'model' => null,
                'title' => 'Data 5'
            ]
        ]
    ];
    public function getListWidgets()
    {
        return $this->widgetSettings;
    }
    public function getWidgetById($id)
    {
        foreach ($this->widgetSettings as $widget) {
            if ($widget['id'] == $id) {
                return WidgetSetting::parse($widget);
            }
        }
        return null;
    }
}
