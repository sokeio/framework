<?php

namespace Sokeio\Platform;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class ItemStatus
{
    private const PATH_STATUS = 'platform/sokeio_status.json';
    private function __construct(private $key) {}
    private function getKeyStatus($id)
    {
        return $this->key . '.' . md5($id) . '.status';
    }
    public function empty()
    {
        return empty(Arr::get(self::$arrData, $this->key, []));
    }
    public function check($id)
    {
        return Arr::get(self::$arrData, $this->getKeyStatus($id), false);
    }
    public function active($id, $onlyOne = false)
    {
        if ($this->check($id)) {
            return;
        }
        if ($onlyOne) {
            Arr::set(self::$arrData, $this->key, []);
        }
        Arr::set(self::$arrData, $this->getKeyStatus($id), true);
        self::saveData();
    }
    public function block($id)
    {
        if (!$this->check($id)) {
            return;
        }
        Arr::set(self::$arrData, $this->getKeyStatus($id), false);
        self::saveData();
    }
    private static $arr = [];
    private static $arrData = [];
    private static function loadData()
    {
        if (!file_exists(base_path(self::PATH_STATUS))) {
            self::saveData();
        }
        self::$arrData = File::json(base_path(self::PATH_STATUS)) ?? [];
    }
    private static function saveData()
    {
        File::put(base_path(self::PATH_STATUS), json_encode(self::$arrData ?? []));
    }
    public static function key($key): self
    {
        if (!isset(self::$arr[$key])) {
            self::$arr[$key] = new self($key);
            self::loadData();
        }
        return self::$arr[$key];
    }
}
