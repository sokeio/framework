<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
if (!function_exists('viewjs')) {
    function viewjs($name)
    {
        $namespaces = explode('::', $name);
        $moduleName = $namespaces[0];
        $path = str_replace('.', '/', $namespaces[1]);
        $item =  Platform::module()->findByNameOrId($moduleName);
        if (!$item) {
            return '';
        }
        $path = $item->getPath("resources/views/{$path}.js");
        if (!File::exists($path)) {
            return '';
        }
        return file_get_contents($path);
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
