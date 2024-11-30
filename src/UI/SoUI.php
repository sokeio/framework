<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Sokeio\Setting;
use Sokeio\UI\Concerns\LifecycleUI;

class SoUI
{
    use LifecycleUI;
    private $actions = [];
    private $wire = null;
    private $fields = [];


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
        if (!is_array($group)) {
            $group = [$group];
        }
        $fields = [];
        foreach ($this->fields as $field) {

            if (in_array($field->getGroup(), $group)) {
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
    public function getRuleForm($group = null)
    {
        $messages = [];
        $rules = [];
        $labels = [];
        foreach ($this->fields as $field) {
            if ($group && $field->getGroup() != $group) {
                continue;
            }
            $messages = array_merge($messages, $field->getRuleMessages());
            $rules = array_merge($rules, $field->getRules());
            $labels[$field->getFieldName()] = $field->getLabel();
        }
        return [
            'rules' => $rules,
            'messages' => $messages,
            'labels' => $labels
        ];
    }
    public function loadInSetting()
    {
        foreach ($this->fields as $field) {
            $field->loadInSetting();
        }
        return $this;
    }
    public function saveInSetting($validate = true)
    {
        if ($validate) {
            $this->validate();
        }
        foreach ($this->fields as $field) {
            $field->saveInSetting();
        }
        Setting::save();
        return $this;
    }
    public function getWire()
    {
        return $this->wire;
    }
    public function validate($field = 'formData', $group = null, $excute = true)
    {
        $rule = $this->getRuleForm($group);
        $data = data_get($this->getWire(), $field);
        if ($data instanceof \Sokeio\FormData) {
            $data = $data->toArray();
        }
        $validator = Validator::make([$field => $data], $rule['rules'], $rule['messages'], $rule['labels']);

        if ($excute) {
            return $validator->validate();
        }
        return $validator;
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
        $this->initLifecycleUI();
        $this->child($ui);
        $this->setupChild(fn($ui) => $ui->registerManager($this));
        $this->setupChild(fn($ui) => $ui->register());
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
}
