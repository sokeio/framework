<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Sokeio\Platform;
use Sokeio\Core\PlatformManager;
use Sokeio\Setting;
use Sokeio\Core\SettingManager;
use Sokeio\Theme\ThemeManager;
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
    function viewjs($name, $theme = false)
    {
        $namespaces = explode('::', $name);
        $moduleName = $namespaces[0];
        $path = str_replace('.', '/', $namespaces[1]);
        if ($theme) {
            // Not implemented yet
            // $item =  Platform::theme()->find($moduleName);
        } else {
            $item =  Platform::module()->find($moduleName);
        }
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

if (!function_exists('sokeioJS')) {
    function sokeioJS($name, $main, array $components = null)
    {
        if (!File::exists($main)) {
            return "";
        }
        $mainJs = file_get_contents($main);
        $componentJS = '';
        if ($components && is_array($components)) {
            foreach ($components as $key => $component) {
                if (!File::exists($component)) {
                    return "";
                }
                $componentJS .= "<template sokeio:component=\"{$key}\">" . file_get_contents($component) . "</template>";
            }
        }

        $html = <<<HTML
                <div wire:ignore>
                    <div sokeio:application="{$name}">
                        <template sokeio:main>
                            {$mainJs}
                        </template>
                        {$componentJS}
                    </div>
                </div>
                HTML;
        return $html;
    }
}
