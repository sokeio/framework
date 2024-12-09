<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Facades\Validator;
use Sokeio\Setting;

trait WireUI
{
    private $actions = [];
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


    public function action($name, $callback, $ui = null, $skipRender = false)
    {
        $this->actions[$name] = [
            'callback' => $callback,
            'ui' => $ui,
            'skipRender' => $skipRender
        ];
    }
    public function removeAction($name)
    {
        if (isset($this->actions[$name])) {
            unset($this->actions[$name]);
        }
    }

    public function callActionUI($name, $params = []): mixed
    {
        if (!array_key_exists($name, $this->actions)) {
            $this->getWire()->alert('Action ' . $name . ' not found');
            return null;
        }

        $action = $this->actions[$name];
        if ($action['skipRender']) {
            $this->getWire()->skipRender();
        }
        // $this->getWire()->alert('Action ' . $name . ' not called');
        if ($action['callback']) {
            return  call_user_func($action['callback'], $params);
        }
        $this->getWire()->alert('Action ' . $name . ' not called');
        return null;
    }
}
