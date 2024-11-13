<?php

use Sokeio\Platform;
use Sokeio\Setting;
use Sokeio\Support\Platform\SettingManager;
use Sokeio\Theme;

if (!function_exists('theme')) {
    function theme()
    {
        return Theme::getFacadeRoot();
    }
}
if (!function_exists('platform')) {
    function platform()
    {
        return Platform::getFacadeRoot();
    }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null): SettingManager|string
    {
        if ($key === null) {
            return Setting::getFacadeRoot();
        }
        return Setting::get($key, $default);
    }
}
