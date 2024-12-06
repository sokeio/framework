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
    private SoUI|null $manager = null;

    public function getManager()
    {
        return $this->manager;
    }
    public function style($style)
    {
        return $this->vars('style', $style);
    }
   
    protected function getNameWithPrefix($name)
    {
        return $this->getPrefix() ? $this->getPrefix() . '.' . $name : $name;
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
        $this->boot(function () {
            $this->attr('sokeio-group', $this->getGroup());
        });
    }
    public function refUI($callback = null)
    {
        if ($callback) {
            call_user_func($callback, $this);
        }
        return $this->manager;
    }
    public function registerManager(SoUI $manager)
    {
        $this->manager = $manager;
        $this->setupChild(fn($c) => $c->registerManager($manager));
        return $this->register(function () {
            $this->initUI();
        });
    }

    protected function initUI()
    {
        // TODO: Implement initUI() method.
    }

    public function action($key, $callback)
    {
        $this->bootAction(function () use ($key, $callback) {
            $this->manager->action($key, $callback, $this);
        });
        return $this;
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
    public static function init($childs = [])
    {
        return new static($childs);
    }
}
