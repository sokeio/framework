<?php

namespace Sokeio\Platform;

class PlatformStatus
{
    private const KEY = 'PLATFORM_STATUS_';
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
        return setting(self::KEY . $this->key, []);
    }
    public function update($arr)
    {
        $this->setStore(self::KEY . $this->key, $arr);
    }
    public function check($id)
    {
        return in_array($id, $this->getArr());
    }
    public function active($id, $onlyOne = false)
    {
        if ($onlyOne) {
            $this->setStore(self::KEY . $this->key, [$id]);
        } else {
            if ($this->check($id)) {
                return;
            }
            $this->setStore(self::KEY . $this->key, array_unique([$id, ...$this->getArr()]));
        }
    }
    public function block($id)
    {
        $this->setStore(self::KEY . $this->key, array_filter($this->getArr(), function ($item) use ($id) {
            return $id !== $item;
        }));
    }
    private function setStore($key, $data)
    {
        setSetting($key, $data);
    }
    private static $arr = [];
    public static function key($key): self
    {
        if (!isset(self::$arr[$key])) {
            self::$arr[$key] = new self($key);
        }
        return self::$arr[$key];
    }
}
