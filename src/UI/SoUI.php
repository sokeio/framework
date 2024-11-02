<?php

namespace Sokeio\UI;

use Sokeio\UI\Support\HookUI;

class SoUI
{
    protected HookUI $hook;
    private $ui = [];
    private $actions = [];
    private $wire = null;
    private $fields = [];

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
    public function registerField($field)
    {
        $this->fields[] = $field;
    }
    public function getFields()
    {
        return $this->fields;
    }
    public function getFieldsByGroup($group)
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->getGroup() == $group) {
                $fields[] = $field;
            }
        }
        return $fields;
    }
    public function fill($model)
    {
        foreach ($this->fields as $field) {
            $field->fillToModel($model);
        }
    }
    public function getRuleForm()
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $rules = array_merge($rules, $field->getRules());
        }
        return $rules;
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
    public function callActionUI($name, $params = []): mixed
    {
        if (!array_key_exists($name, $this->actions)) {
            $this->getWire()->alert('Action ' . $name . ' not found');
            return null;
        }
        $action = $this->actions[$name];
        if ($action['callback']) {
            return  call_user_func($action['callback'], $params);
        }
        $this->getWire()->alert('Action ' . $name . ' not called');
        return null;
    }
    public function __construct($ui = [], $wire = null)
    {
        $this->wire = $wire;
        if (!$ui) {
            $ui = [];
        }
        if (!is_array($ui)) {
            $ui = [$ui];
        }
        $this->hook = new HookUI();
        $this->ui = $ui;
        $this->initManager();
        $this->register();
    }
    public function tapUI($callback)
    {
        foreach ($this->ui as $ui) {
            if ($ui instanceof BaseUI) {
                $callback($ui);
            }
        }
        return $this;
    }
    public function initManager()
    {
        return $this->tapUI(fn($ui) => $ui->registerManager($this));
    }
    public function register($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('register', $callback);
            return $this;
        }
        $this->lifecycleWithKey('register', $callback, (func_get_args()));

        //register
        return $this->tapUI(fn($ui) => $ui->register());
    }
    public function boot($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('boot', $callback);
            return $this;
        }
        $this->lifecycleWithKey('boot', $callback, (func_get_args()));

        //boot
        return $this->tapUI(fn($ui) => $ui->boot());
    }
    public function ready($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('ready', $callback);
            return $this;
        }
        $this->lifecycleWithKey('ready', $callback, (func_get_args()));
        //ready
        return $this->tapUI(fn($ui) => $ui->ready());
    }
    private function getHtmlItem($ui, $params = null, $callback = null)
    {
        $html = '';
        if (is_array($ui)) {
            $html = implode('', $ui);
        }
        if (is_callable($ui)) {
            $html = call_user_func($ui);
        }
        if ($ui instanceof BaseUI) {
            $rs = null;
            if ($callback) {
                $rs = call_user_func($callback, $ui);
            }
            $ui->setParams($params);
            $ui->render();
            if ($ui->checkWhen()) {
                $html = $ui->view();
            }
            $ui->clearParams();
            if ($rs && is_callable($rs)) {
                call_user_func($rs, $ui);
            }
        }

        return $html;
    }
    public function getHtml($uis, $params = null, $callback = null)
    {
        if (!$uis) {
            return '';
        }
        $html = '';
        foreach ($uis as $child) {
            $html .= $this->getHtmlItem($child, $params, $callback);
        }
        return $html;
    }
    public function render($callback = null)
    {
        if ($callback) {
            $this->lifecycleWithKey('render', $callback);
            return $this;
        }
        $this->lifecycleWithKey('render', $callback, (func_get_args()));
        //render
        $this->tapUI(fn($ui) => $ui->render());
        $html = '';
        foreach ($this->ui as $ui) {
            if ($ui instanceof BaseUI) {
                $html .= $ui->view();
            } elseif (is_array($ui)) {
                $html .= implode('', $ui);
            } elseif (is_callable($ui)) {
                $html .= call_user_func($ui, $this);
            } else {
                $html .= $ui;
            }
        }
        if (count($this->ui) > 1) {
            return '<div>' . $html . '</div>';
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
    public function toHtml()
    {
        $this->boot();
        $this->ready();
        return $this->render();
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
