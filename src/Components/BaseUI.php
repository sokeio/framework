<?php

namespace Sokeio\Components;

class BaseUI
{
    private $childs = [];
    private $data = [];
    private $callbackReady = [];
    public function ready($callback)
    {
        if (!is_callable($callback)) {
            return $this;
        }
        $this->callbackReady[] = $callback;
        return $this;
    }
    public function register()
    {
        // Khởi tạo các child element trong component
        return $this;
    }
    public function boot()
    {
        // boot các child element
        return $this;
    }
    public function render()
    {
        return "";
    }
    public function toArray()
    {
        $childs = [];
        foreach ($this->childs as $key => $child) {
            if (is_array($child)) {
                foreach ($child as $k => $c) {
                    $childs[$key][$k] = $c->toArray();
                }
            } else {
                $childs[$key] = $child->toArray();
            }
        }
        return [
            'ui' => self::class,
            'data' => ($this->data),
            'childs' => $childs
        ];
    }

    public static function toUI($arr)
    {
        [
            'ui' => $ui,
            'data' => $data,
            'childs' => $childs
        ] = $arr;
        return $ui::create($data, $childs);
    }

    public static function create($data, $childs = [])
    {
        $instance = new static();
        $instance->data = $data;
        if ($childs) {
            foreach ($childs as $key => $child) {
                if (is_array($child)) {
                    foreach ($child as $k => $c) {
                        $instance->childs[$key][$k] = self::toUI($c);
                    }
                } else {
                    $instance->childs[$key] = self::toUI($child);
                }
            }
        }

        return $instance;
    }
}
