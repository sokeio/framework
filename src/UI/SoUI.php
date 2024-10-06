<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Log;
use Sokeio\UI\Support\HookUI;

class SoUI
{
    protected HookUI $hook;
    private $ui = [];
    private $actions = [];
    private $wire = null;
    private function lifecycleWithKey($key, $callback = null, $params = null)
    {
        if ($callback) {
            $this->hook->group($key)->callback($callback);
        } else {
            if (count($params) > 1) {
                $this->hook->group($key)->run([$this, ...array_shift($params)]);
            } else {
                $this->hook->group($key)->run([$this]);
            }
        }
        return $this;
    }
    public function getWire()
    {
        return $this->wire;
    }
    public function when($condition, $callback, $ui = null)
    {
        if (is_callable($condition)) {
            $condition = $condition();
        }
        if ($condition) {
            if ($ui) {
                $callback($ui);
            } else {
                $callback();
            }
        }
        return $this;
    }
    public function action($name, $callback, $ui = null)
    {
        $this->actions[$name] = [
            'callback' => $callback,
            'ui' => $ui
        ];
    }
    public function callAction($name, $params = [])
    {
        $action = $this->actions[$name];
        if ($action['callback']) {
            call_user_func($action['callback'], $params);
        }
    }
    public function __construct($ui = [], $wire = null)
    {
        $this->wire = $wire;
        if (!$ui) {
            return;
        }
        if (!is_array($ui)) {
            $ui = [$ui];
        }
        $this->hook = new HookUI();
        $this->ui = $ui;
        $this->initManager();
        $this->register();
    }
    public function initManager()
    {
        foreach ($this->ui as $ui) {
            $ui->registerManager($this);
        }
    }
    public function register($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('register', $callback);
            return $this;
        }
        $this->lifecycleWithKey('register', $callback, (func_get_args()));

        //register
        foreach ($this->ui as $ui) {
            $ui->register();
        }
        return $this;
    }
    public function boot($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('boot', $callback);
            return $this;
        }
        $this->lifecycleWithKey('boot', $callback, (func_get_args()));

        //boot
        foreach ($this->ui as $ui) {
            $ui->boot();
        }
        return $this;
    }
    public function ready($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('ready', $callback);
            return $this;
        }
        $this->lifecycleWithKey('ready', $callback, (func_get_args()));
        //ready
        foreach ($this->ui as $ui) {
            $ui->ready();
        }
        return $this;
    }
    public function render($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('render', $callback);
            return $this;
        }
        $this->lifecycleWithKey('render', $callback, (func_get_args()));
        //render
        foreach ($this->ui as $ui) {
            $ui->render();
        }
        //render
        $html = '';
        foreach ($this->ui as $ui) {
            $html .= $ui->view();
        }
        if (count($this->ui) > 1) {
            return '<div >' . $html . '</div>';
        }
        return $html;
    }
    public function toArray()
    {
        $ui = [];
        foreach ($this->ui as $key => $value) {
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
        return static::init($ui, $wire)->render();
    }
}
