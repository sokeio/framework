<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Sokeio\Livewire\Platform\Item;

class DashboardManager
{
    const PATH = "platform/dashboard.json";
    private $widgets = [];
    private $setting = null;
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
        return data_get($this->getWidgets(), $key . '.class');
    }
    public function getDashboard($key = 'default')
    {
        return  collect($this->getWidgets())->where(function ($value) use ($key) {
            return $value['info']->dasboard === $key;
        });
    }
    public function settingWidgets($key = null, $value = null, $dashboard = 'default')
    {
        if (! $this->setting) {
            if (!File::exists(base_path(self::PATH))) {
                $widgets =  collect($this->getWidgets())->map(function ($value) {
                    return  [
                        'key' => $value['key'],
                        'name' => $value['info']->name,
                        'dashboard' => $value['info']->dasboard,
                        'show' => $value['info']->show

                    ];
                })->groupBy(function ($item) {
                    return $item['dashboard'];
                })->toArray();
                File::put(base_path(self::PATH), json_encode($widgets));
            }
            $this->setting = json_decode(File::get(base_path(self::PATH)), true);
        }
        $keyFirst = explode('.', $key)[0];
        $keyWithoutFirst = explode('.', $key)[1];
        $setting = $this->setting;
        $item = null;
        foreach ($setting[$dashboard] as $item1) {
            if ($item1['key'] == $keyFirst) {
                $item = $item1;
                break;
            }
        }
        if (!$item) {
            return null;
        }
        if ($value !== null) {
            $arry = [];
            foreach ($setting[$dashboard] as $item1) {
                if ($item1['key'] == $keyFirst) {
                    $item1[$keyWithoutFirst] = $value;
                    $arry[] = $item1;
                } else {
                    $arry[] = $item1;
                }
            }
            $this->setting[$dashboard] = $arry;
            File::put(base_path(self::PATH), json_encode($this->setting));
        }
        return $item[$keyWithoutFirst] ?? null;
    }
    public function setDataDashboard($key, $value)
    {
        Session::put($key, $value);
    }
    public function getDataDashboard($key = 'default')
    {
        return Session::get($key);
    }
}
