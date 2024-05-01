<?php

namespace Sokeio\Platform;

class PlatformStatus
{
    private const PATH_STATUS = 'platform/sokeio_status.json';
    private function __construct(private $key)
    {
    }

    public function getFirstOrDefault($default = null)
    {
        $arr = $this->getArr();
        if ($arr && !empty($arr[0])) {
            return  $arr[0];
        }
        return $default;
    }
    public function getArr()
    {
        return self::$arrData[$this->key] ?? [];
    }
    public function update($arr)
    {
        self::$arrData[$this->key] = $arr;
        self::saveData();
    }
    public function option($key, $data)
    {
        if (!isset(self::$arrData[$this->key . '_OPTIONS'])) {
            self::$arrData[$this->key . '_OPTIONS'] = [];
        }
        self::$arrData[$this->key . '_OPTIONS'][$key] = $data;
    }
    public function remove($id)
    {
        $this->update(array_filter($this->getArr(), function ($item) use ($id) {
            return $id !== $item;
        }));
    }
    public function check($id)
    {
        return in_array($id, $this->getArr());
    }
    public function active($id, $onlyOne = false)
    {
        if ($onlyOne) {
            $this->update([$id]);
        } else {
            if ($this->check($id)) {
                return;
            }
            $this->update(array_merge($this->getArr(), [$id]));
        }
    }
    public function block($id)
    {
        $this->remove($id);
    }
    private static $arr = [];
    private static $arrData = [];
    private static function loadData()
    {
        if (!file_exists(base_path(self::PATH_STATUS))) {
            self::saveData();
        }
        self::$arrData = json_decode(file_get_contents(base_path(self::PATH_STATUS)), true) ?? [];
    }
    private static function saveData()
    {
        file_put_contents(base_path(self::PATH_STATUS), json_encode(self::$arrData ?? []));
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
