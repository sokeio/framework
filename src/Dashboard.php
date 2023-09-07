<?php

namespace BytePlatform;

class Dashboard
{
    private const KEY_STATUS = 'DASHBOARD_WIDGET';
    private static $_dashboards = [];
    public static function Register($dashboards, $namespace, $force = false)
    {
        if (is_array($dashboards)) {
            foreach ($dashboards as $item) {
                self::RegisterItem($item->getKey(), $item, $namespace, $force);
            }
            return;
        }
    }
    public static function RegisterItem($key, $item, $namespace, $force = false)
    {
        $dashboardKey = $namespace . '::' . $key;
        if (!isset(self::$_dashboards[$dashboardKey])) {
            self::$_dashboards[$dashboardKey] = $item;
            return;
        }
        if (isset(self::$_dashboards[$key]) && $force) {
            self::$_dashboards[$dashboardKey] = $item;
            return;
        }
    }
    public static function GetWidgets()
    {
        return self::$_dashboards;
    }
    public static function getWidgetByKey($key)
    {
        return is_string($key) && isset(self::$_dashboards[$key]) ? self::$_dashboards[$key] : null;
    }
    public static function ActiveWidget($widgetId, $widgetSetting)
    {
        ArrayStatus::Key(self::KEY_STATUS)->Active($widgetId);
        set_setting(self::KEY_STATUS . '-' . $widgetId, $widgetSetting);
    }
    public static function UnActiveWidget($widgetId)
    {
        ArrayStatus::Key(self::KEY_STATUS)->UnActive($widgetId);
        set_setting(self::KEY_STATUS . '-' . $widgetId, null);
    }
    public static function getWidgetSettingByKey($widgetId)
    {
        return setting(self::KEY_STATUS . '-' . $widgetId, []);
    }
    public static function GetWidgetActives()
    {
        return collect(ArrayStatus::Key(self::KEY_STATUS)->getArr())->map(function ($item) {
            return self::getWidgetSettingByKey($item);
        })->where(function ($item) {
            return $item != null;
        });
    }
    public static function GetWidgetIdActives()
    {
        return collect(ArrayStatus::Key(self::KEY_STATUS)->getArr())->map(function ($item) {
            return self::getWidgetSettingByKey($item);
        })->where(function ($item) {
            return $item != null;
        })->map(function ($item) {
            return $item['widgetId'];
        });
    }
    public static function Update($arr)
    {
        ArrayStatus::Key(self::KEY_STATUS)->Update($arr);
    }
}
