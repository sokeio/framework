<?php

namespace Sokeio\UI;

use Sokeio\UI\Concerns\CommonUI;
use Sokeio\UI\Concerns\LifecycleUI;

class BaseUI
{
    use LifecycleUI, CommonUI;

    public function icon($icon)
    {
        return $this->vars('icon', $icon);
    }
    public function getIcon()
    {
        $icon = $this->getVar('icon', null, true);
        if ($icon && !str($icon)->trim()->startsWith('<i')) {
            $icon = '<i class=" ' . $icon . '"></i>';
        }
        return $icon;
    }
    private $callbackView = null;

    public function style($style)
    {
        return $this->vars('style', $style);
    }

    protected function getNameWithPrefix($name)
    {
        if ($prefix = $this->getPrefix()) {
            if ($groupField = $this->getGroupField()) {
                return $prefix . '.' . $groupField . '.' . $name;
            }
            return $prefix . '.' . $name;
        }
        return  $name;
    }
    public function getWire()
    {
        return $this->getManager()?->getWire();
    }
    public function getWireValue($key, $default = null)
    {
        $wire = $this->getWire();
        return data_get($wire, $key, $default);
    }
    public function changeValue($key, $value, $withPrefix = true)
    {
        $wire = $this->getWire();
        if ($withPrefix) {
            $key = $this->getNameWithPrefix($key);
        }
        data_set($wire, $key, $value);
    }
    public function getValueByKey($key, $default = null)
    {
        $wire = $this->getWire();
        return data_get($wire, $this->getNameWithPrefix($key), $default);
    }
    protected function __construct($childs = [])
    {
        $this->initLifecycleUI();
        $this->initCommonUI();
        $this->child($childs);
        $this->initUI();
        $this->register(function () {
            $this->registerIDChild();
            $this->attr('sokeio-group', $this->getGroup());
            $this->attr('sokeio-ui-id', $this->getUIID());
            $this->attr('sokeio-ui-key', $this->getUIIDkey());
        });
    }
    public function refUI($callback = null)
    {
        if ($callback) {
            call_user_func($callback, $this);
        }
        return $this->manager;
    }

    protected function initUI()
    {
        // TODO: Implement initUI() method.
    }

    public function action($key, $callback, $skipRender = false)
    {
        return $this->boot(function () use ($key, $callback, $skipRender) {
            $this->manager->action($key, $callback, $this, $skipRender);
        })->afterRender(function ()  use ($key) {
            $this->manager->removeAction($key);
        });
    }
    public function setView($callback)
    {
        $this->callbackView = $callback;
        return $this;
    }
    public function view()
    {
        if ($this->callbackView) {
            return call_user_func($this->callbackView, $this);
        }
        $attr = $this->getAttr();
        return <<<HTML
        <div {$attr}></div>
        HTML;
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
            'ui' => static::class,
            'data' => ($this->data),
            'childs' => $childs
        ];
    }

    public static function toUI($arr)
    {
        if (!$arr) {
            return [];
        }
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
                        $instance->childs[$key][$k] = self::toUI($c)->setParent($instance);
                    }
                } else {
                    $instance->childs[$key] = self::toUI($child)->setParent($instance);
                }
            }
        }

        return $instance;
    }
    public static function make($childs = [])
    {
        return new static($childs);
    }
}
