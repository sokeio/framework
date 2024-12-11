<?php

use Sokeio\Platform;
use Sokeio\Support\Platform\PlatformManager;
use Sokeio\Setting;
use Sokeio\Support\Platform\SettingManager;
use Sokeio\Support\Theme\ThemeManager;
use Sokeio\Theme;

if (!function_exists('theme')) {
    function theme(): ThemeManager
    {
        return Theme::getFacadeRoot();
    }
}
if (!function_exists('platform')) {
    function platform(): PlatformManager
    {
        return Platform::getFacadeRoot();
    }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null): SettingManager|string|array|null
    {
        if ($key === null) {
            return Setting::getFacadeRoot();
        }
        return Setting::get($key, $default);
    }
}

if (!function_exists('tagLink')) {
    function tagLink($link)
    {
        return '<a href="' . $link . '" target="_blank">' . $link . '</a>';
    }
}

if (!function_exists('themeOption')) {
    function themeOption($key, $default = null)
    {
        return Theme::option($key, $default);
    }
}

if (!function_exists('themeLocation')) {
    function themeLocation($location)
    {
        return Theme::renderLocation($location);
    }
}
