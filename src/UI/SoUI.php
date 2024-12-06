<?php

namespace Sokeio\UI;

use Sokeio\UI\Common\Card;
use Sokeio\UI\Concerns\LifecycleUI;
use Sokeio\UI\Concerns\WithSoUI;

class SoUI
{
    use LifecycleUI, WithSoUI;
    public function __construct($ui = [], $wire = null)
    {
        $this->wire = $wire;
        $this->initLifecycleUI();
        $this->child($ui);
        $this->registerManager($this);
        $this->register();
    }
    public function toArray()
    {
        $ui = [];
        foreach ($this->childs as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $ui[$key][$k] = $v->toArray();
                }
            } else {
                $ui[$key] = $value->toArray();
            }
        }
        return $ui;
    }
    public function render($callback = null)
    {
        if (!$callback) {
            $this->lifecycleWithKey('render', $callback, (func_get_args()));
            return $this->renderChilds();
        }
        return $this->lifecycleWithKey('render', $callback, (func_get_args()));
    }
    public function toHtml()
    {
        $this->boot();
        $this->ready();
        return $this->renderChilds();
    }
    public function toUI($arr)
    {
        return BaseUI::toUI($arr);
    }
    public static function init($ui, $wire = null)
    {
        return new SoUI($ui, $wire);
    }
    public static function renderUI($ui, $wire = null)
    {
        return static::init($ui, $wire)->toHtml();
    }
    private static $uiWithKey = [];
    private static $uiWithGroupKey = [];
    public static function registerUI($ui, $key = null, $group = null,  $tapCard = null)
    {
        if (!is_array($ui)) {
            $ui = [$ui];
        }
        if ($key) {
            if (!isset(static::$uiWithKey[$key])) {
                static::$uiWithKey[$key] = [];
            }

            static::$uiWithKey[$key] = array_merge(static::$uiWithKey[$key], array_map(function ($u) {
                return clone $u;
            }, $ui));
        }

        if ($group) {
            if (!isset(static::$uiWithGroupKey[$group])) {
                static::$uiWithGroupKey[$group] = [];
            }
            if ($tapCard) {
                static::$uiWithGroupKey[$group] = array_merge(
                    static::$uiWithGroupKey[$group],
                    [
                        Card::init($ui)
                            ->tap($tapCard)
                    ]
                );
            } else {
                static::$uiWithGroupKey[$group] = array_merge(static::$uiWithGroupKey[$group], $ui);
            }
        }
    }
    public static function getUI($key)
    {
        return static::$uiWithKey[$key] ?? [];
    }
    public static function getGroupUI($group)
    {
        return static::$uiWithGroupKey[$group] ?? [];
    }
}
