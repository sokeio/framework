<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Facades\Validator;
use Sokeio\Setting;
use Sokeio\UI\Field\FieldUI;

trait WireUI
{
    private $actions = [];
    /** @var FieldUI[] */
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
    public function fillWithId($model,  $prefix, $group, $id = null)
    {
        foreach ($this->fields as $field) {
            if ($group && $field->getUIGroup() != $group) {
                continue;
            }
            $prefix_temp = $field->getPrefix();
            if ($prefix && $id) {
                $field->prefix($prefix . '.' . $id);
            }
            $field->fillToModel($model);
            $field->prefix($prefix_temp);
        }
    }
    private function ruleForm(&$messages, &$rules, &$labels, $group = null, $prefix = null, $id = null)
    {
        foreach ($this->fields as $field) {
            if ($group && $field->getUIGroup() != $group) {
                continue;
            }
            $prefix_temp = $field->getPrefix();
            if ($prefix && $id) {
                $field->prefix($prefix . '.' . $id);
            }
            $messages = array_merge($messages, $field->getRuleMessages());
            $rules = array_merge($rules, $field->getRules());
            $label = $field->getLabel();
            if (!$label) {
                $label = $field->getFieldName();
            }
            $arr = explode('.', $label);
            $label = end($arr);

            $labels[$field->getFieldName()] = $label;
            $field->prefix($prefix_temp);
        }
    }
    public function getRuleForm($group = null, $prefix = null, $id = null)
    {
        $messages = [];
        $rules = [];
        $labels = [];
        if ($id && is_array($id)) {
            foreach ($id as $key => $value) {
                $this->ruleForm($messages, $rules, $labels, $group, $prefix, $value);
            }
        } else {
            $this->ruleForm($messages, $rules, $labels, $group, $prefix, $id);
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
    public function validate($field = 'formData', $group = null, $excute = true, $id = null)
    {
        $rule = $this->getRuleForm($group, $field, $id);
        // dd($rule);
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


    public function action(
        $name,
        $callback,
        $ui = null,
        $skipRender = false
    ) {
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

    public function callActionUI($name, $params = [], &$uirefresh = null): mixed
    {
        if (!array_key_exists($name, $this->actions)) {
            $this->getWire()->alert('Action ' . $name . ' not found');
            return null;
        }

        $action = $this->actions[$name];
        $uirefresh = true;

        if ($action['callback']) {

            if (!is_array($params)) {
                $params = [$params];
            }
            $params[] = $action['ui'];
            $rs =  call_user_func_array($action['callback'], $params);
            if ($action['skipRender']) {
                $uirefresh = false;
                $this->getWire()->skipRender();
            }
            return $rs;
        }
        $this->getWire()->alert('Action ' . $name . ' not called');
        return null;
    }
}
