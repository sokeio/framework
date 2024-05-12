<?php

namespace Sokeio\Dashboard;

class DashboardManager
{
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
                        "column" => "col4",
                        "title" => "Users",
                        "icon" => "bi bi-person",
                        "model" => "Sokeio\\Models\\User"
                    ]
                ],
                [
                    "id" => "widget-2",
                    "type" => "sokeio::widgets.model-count",
                    "position" => "WIDGET_HEADER",
                    "options" => [
                        "column" => "col4",
                        "title" => "Roles",
                        "icon" => "bi bi-person-badge",
                        "model" => "Sokeio\\Models\\Role"
                    ]
                ],
                [
                    "id" => "widget-3",
                    "type" => "sokeio::widgets.model-count",
                    "position" => "WIDGET_HEADER",
                    "options" => [
                        "column" => "col4",
                        "title" => "Permissions",
                        "icon" => "bi bi-key",
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
        if (isset($widget['class']) && $temp = app($widget['class'])) {
            return $temp->boot()->component($component)->option($widget['options'] ?? []);
        }
        return null;
    }

    public function getPosition()
    {
        return [
            [
                'id' => 'WIDGET_HEADER',
                'title' => 'Header',
                'class' => 'dashboard-header',
            ],
            [
                'id' => 'WIDGET_BODY',
                'title' => 'Body',
                'class' => 'dashboard-body',
            ],
            [
                'id' => 'WIDGET_FOOTER',
                'title' => 'Footer',
                'class' => 'dashboard-footer',
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
