<?php

namespace Sokeio;

class ArrayStatus
{
    private const __KEY__ = 'ARRAY_STATUS_';
    private function __construct(private $key)
    {
    }
    public function getFirstOrDefault($default = null)
    {
        $arr = $this->getArr();
        if ($arr && count($arr) > 0) {
            return  $arr[0];
        }
        return $default;
    }
    public function getArr()
    {
        return setting(self::__KEY__ . $this->key, []);
    }
    public function Update($arr)
    {
        set_setting(self::__KEY__ . $this->key, $arr);
    }
    public function Check($id)
    {
        return in_array($id, $this->getArr());
    }
    public function Active($id, $onlyOne = false)
    {
        if ($onlyOne) {
            set_setting(self::__KEY__ . $this->key, [$id]);
        } else {
            if ($this->Check($id)) return;
            set_setting(self::__KEY__ . $this->key, array_unique([$id, ...$this->getArr()]));
        }
    }
    public function UnActive($id)
    {
        set_setting(self::__KEY__ . $this->key, array_filter($this->getArr(), function ($item) use ($id) {
            return $id !== $item;
        }));
    }
    private static $arr = [];
    public static function Key($key): self
    {
        if (!isset(self::$arr[$key])) {
            self::$arr[$key] = new self($key);
        }
        return self::$arr[$key];
    }
}
