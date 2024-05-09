<?php

namespace Sokeio\Dashboard;

class DashboardManager
{
    // private const DASHBOARD_WIDGET_STATUS = 'DASHBOARD_WIDGET_STATUS';
    // private const DASHBOARD_WIDGET_TEMP = 'DASHBOARD_WIDGET_TEMP';
    private $widgets = [];
    private $data = [
        [
            "id" => "dashboard-default",
            "name" => "Dashboard Default",
            "default" => true,
            "widgets" => [
                [
                    "id" => "widget-1",
                    "type" => "sokeio::widgets.model-count",
                    "position" => "WIDGET_HEADER",
                    "options" => [
                        "title" => "Users",
                        "model" => "Sokeio\\Models\\User"
                    ]
                ],
                [
                    "id" => "widget-2",
                    "type" => "sokeio::widgets.model-count",
                    "position" => "WIDGET_HEADER",
                    "options" => [
                        "title" => "Users",
                        "model" => "Sokeio\\Models\\Role"
                    ]
                ],
                [
                    "id" => "widget-3",
                    "type" => "sokeio::widgets.model-count",
                    "position" => "WIDGET_HEADER",
                    "options" => [
                        "title" => "Users",
                        "model" => "Sokeio\\Models\\Permission"
                    ]
                ]

            ]
        ]
    ];
    public function getData()
    {
        return $this->data;
    }
    public function getDashboard($id)
    {
        if ($id) {
            foreach ($this->data as  $value) {
                if ($value['id'] == $id) {
                    return $value;
                }
            }
        }
        foreach ($this->data as  $value) {
            if ($value['default'] === true) {
                return $value;
            }
        }
        return null;
    }
    public function getWidgetInDashboard($dashboardId = null)
    {
        $dashboard = $this->getDashboard($dashboardId);
        if ($dashboard) {
            return collect($dashboard['widgets'] ?? [])->map(function ($widget) use ($dashboard) {
                return [
                    ...$widget,
                    'dashboardId' => $dashboard['id'],
                    'class' => $this->getWidgetClassByKey($widget['type'])
                ];
            });
        }
        return null;
    }
    public function getWidget($dashboardId, $widgetId)
    {
        $widgets = $this->getWidgetInDashboard($dashboardId);
        if ($widgets) {
            return $widgets->where('id', $widgetId)->first();
        }
        return $widgets;
    }
    public function getWidgetComponent($dashboardId, $widgetId, $component)
    {
        $widget = $this->getWidget($dashboardId, $widgetId);
        print_r($widget);
        if (isset($widget['class']) && $temp = app($widget['class'])) {
            return $temp->boot()->component($component);
        }
        return null;
    }

    public function getPosition()
    {
        return [
            [
                'id' => 'WIDGET_BODY',
                'title' => 'Body',
            ],
            [
                'id' => 'WIDGET_HEADER',
                'title' => 'Header',
            ],
            [
                'id' => 'WIDGET_FOOTER',
                'title' => 'Footer',
            ]
        ];
    }
    public function register($key)
    {
        $this->widgets[md5($key)] = [
            'key' => ($key)::getId(),
            'class' => $key,
        ];
    }
    public function getWidgetType()
    {
        return collect($this->widgets)->values();
    }
    public function getWidgetClassByKey($key)
    {
        $temp = collect($this->widgets)->where('key', $key)->first();
        if ($temp) {
            return $temp['class'];
        }
        return null;
    }
}
