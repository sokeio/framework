<?php

namespace Sokeio\UI;

use Sokeio\UI\Concerns\CommonUI;
use Sokeio\UI\Concerns\LifecycleUI;

class BaseUI
{
    use LifecycleUI, CommonUI;
    private $childs = [];
    private SoUI|null $manager;
    private BaseUI  $parent;
    protected function __construct()
    {
        $this->initLifecycleUI();
        $this->initCommonUI();
    }
    public function when($condition, $callback)
    {
        $this->register(function () use ($condition, $callback) {
            $this->manager->when($condition, $callback, $this);
        });
        return $this;
    }
    public function registerManager(SoUI $manager)
    {
        $this->manager = $manager;
        $this->register(function () {
            $this->initUI();
        });
        return $this;
    }

    protected function initUI()
    {
        // TODO: Implement initUI() method.
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function setParent(BaseUI $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function action($key, $callback)
    {
        $this->ready(function () use ($key, $callback) {
            $this->manager->action($key, $callback, $this);
        });
        return $this;
    }

    public function view()
    {
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
            'ui' => self::class,
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
    public static function init()
    {
        return new static();
    }
}
