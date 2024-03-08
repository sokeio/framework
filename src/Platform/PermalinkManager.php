<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Sokeio\Models\Permalink;

class PermalinkManager
{
    private static $permalinks = [];
    public static function getDefault()
    {
        return self::$permalinks;
    }
    public static function route($key, $permalink, $route_class, $route_name)
    {
        $permalinks = static::getPermalink($key, $permalink);
        foreach ($permalinks as $value) {
            if (isset($value['lang']) && $value['lang'] != '' && $value['lang'] != 'null') {
                $url = str_replace('{lang}', $value['lang'], $value['value']);
                Route::get($url, routeTheme($route_class))->name($route_name . '.' . $value['lang']);
            } else {
                Route::get($value['value'], routeTheme($route_class))->name($route_name);
            }
        }
    }
    public static function getPermalink($key, $permalink)
    {
        self::$permalinks[$key] = $permalink;
        $permalinks = self::getPermalinkCache();
        if (isset($permalinks[$key]) && count($permalinks[$key]) > 0) {
            return $permalinks[$key];
        }
        return [
            [
                'key' => $key,
                'lang' => '',
                'value' =>  $permalink,
                'status' => true
            ]
        ];
    }
    public static function getPermalinkCache()
    {
        return Cache::rememberForever(Permalink::KEY_CACHE, function () {
            try {
                $all = Permalink::query()->where('status', true)->get();
                $values = [];
                foreach ($all as $item) {
                    if (!isset($values[$item->key])) {
                        $values[$item->key] = [];
                    }
                    $values[$item->key][] = [
                        'key' => $item->key,
                        'lang' => $item->lang,
                        'value' => $item->value,
                        'status' => $item->status,
                    ];
                }
                return $values;
            } catch (\Exception $e) {
                return [];
            }
        });
    }
}
