<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class PlatformInfo
{
    private const FILE_PATH = 'platform/sokeio.json';
    private static $data = [];
    public static function loadData()
    {
        if (empty(self::$data)) {
            $filePath = base_path(self::FILE_PATH);
            if (File::exists($filePath)) {
                self::$data = json_decode(File::get($filePath), true);
            }
        }
        return self::$data;
    }
    public static function reloadData()
    {
        self::$data = [];
        return self::loadData();
    }
    public static function saveData()
    {
        $filePath = base_path(self::FILE_PATH);
        File::put($filePath, json_encode(self::$data));
    }
    public static function set($key, $value)
    {
        Arr::set(self::loadData(), $key, $value);
        self::saveData();
    }
    public static function get($key)
    {
        return Arr::get(self::loadData(), $key);
    }
}
