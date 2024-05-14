<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DashboardManager
{
    const PATH_DASHBOARD = 'platform/sokeio_dashboard.json';
    private $widgets = [];
    private $data;
    private $dataDefault = [
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
                        "column" => "col6",
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
    public function store($dashboardId, $widgets)
    {
        $data = $this->getData();
        foreach ($data as $key => $value) {
            if ($value['id'] == $dashboardId) {
                $data[$key]['widgets'] = $widgets;
                $this->data = $data;
                break;
            }
        }

        file_put_contents(base_path(self::PATH_DASHBOARD), json_encode($data));
    }
    public function getData()
    {
        if (File::exists(base_path(self::PATH_DASHBOARD))) {
            $this->data = json_decode(file_get_contents(base_path(self::PATH_DASHBOARD)), true) ?? $this->dataDefault;
        } else {
            $this->data = $this->dataDefault;
        }
        return $this->data;
    }
    public function getDashboard($id)
    {
        if ($id) {
            foreach ($this->getData() as  $value) {
                if ($value['id'] == $id) {
                    return $value;
                }
            }
        }
        foreach ($this->getData() as  $value) {
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
                    'dashboardId' => $dashboard['id']
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
        if (isset($widget['type']) && $temp = app($this->getWidgetClassByKey($widget['type']))) {
            return $temp->boot()->component($component)->option($widget['options'] ?? []);
        }
        Log::info(['Widget not found', 'widgetId' => $widgetId, 'dashboardId' => $dashboardId]);
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
            'id' => ($key)::getId(),
            'title' => ($key)::getTitle(),
            'class' => $key,
        ];
    }
    public function getWidgetType()
    {
        return collect($this->widgets)->values();
    }
    public function getWidgetClassByKey($key)
    {
        $temp = collect($this->widgets)->where('id', $key)->first();
        if ($temp) {
            return $temp['class'];
        }
        return null;
    }
}
