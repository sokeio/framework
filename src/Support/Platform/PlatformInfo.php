<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class PlatformInfo
{
    private const FILE_PATH = 'platform/sokeio.json';
    private static $data = [];
    private static $skipSave = false;
    public static function skipSave()
    {
        self::$skipSave = true;
    }
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
        self::$skipSave = false;
    }
    protected static function set($key, $value)
    {
        Arr::set(self::loadData(), $key, $value);
        if (!self::$skipSave) {
            self::saveData();
        }
    }
    protected static function get($key, $default = null)
    {
        return Arr::get(self::loadData(), $key, $default);
    }

    private static $arr = [];
    public static function key($key): self
    {
        if (!isset(self::$arr[$key])) {
            self::$arr[$key] = new self($key);
        }
        return self::$arr[$key];
    }
    private function __construct(private $key) {}
    public function getValue($key)
    {
        return self::get($this->key . '.' . $key);
    }
    public function setValue($key, $value)
    {
        self::set($this->key . '.' . $key, $value);
    }
    private function getKeyStatus($id)
    {
        return $this->key . '.' . md5($id) . '.status';
    }
    public function empty()
    {
        return empty(self::get($this->key, []));
    }
    public function check($id)
    {
        return self::get($this->getKeyStatus($id), false);
    }
    public function active($id, $onlyOne = false)
    {
        if ($this->check($id)) {
            return;
        }
        self::skipSave();
        if ($onlyOne) {
            self::set($this->key, []);
        }
        self::set($this->getKeyStatus($id), true);
        self::saveData();
    }
    public function block($id)
    {
        if (!$this->check($id)) {
            return;
        }
        self::set($this->getKeyStatus($id), false);
    }
}
