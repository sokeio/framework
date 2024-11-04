<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Rule\WithRule;

class FieldUI extends BaseUI
{
    use WithRule;
    protected function initUI()
    {
        parent::initUI();
        $this->register(function () {
            $this->getManager()->registerField($this);
        });
        $this->render(function () {
            if (!$this->containsAttr('class', 'sokeio-field-input', 'wrapper')) {
                $this->classNameWrapper('sokeio-field-input');
            }
            $debounce = $this->getVar('wire:debounce', null, true);
            if ($debounce && $debounce > 0) {
                $this->attr('wire:model.live.debounce.' . $debounce . 'ms', $this->getFieldName());
            } else {
                $this->attr('wire:model', $this->getFieldName());
            }
        });
    }
    private $fillCallback = null;
    public function fill($callback)
    {
        $this->fillCallback = $callback;
    }
    public function fillToModel($model)
    {
        if ($this->fillCallback && is_callable($this->fillCallback)) {
            call_user_func($this->fillCallback, $model, $this);
        } else {
            $model->{$this->getFieldName()} = $this->getValue();
        }
    }
    public function label($label)
    {
        return $this->vars('label', $label);
    }
    public function classNameWrapper($className)
    {
        return $this->attrAdd('class', $className, 'wrapper');
    }
    protected function getNameWithPrefix($name)
    {
        return $this->getPrefix() ? $this->getPrefix() . '.' . $name : $name;
    }
    public function getFieldNameWithoutPrefix()
    {
        return $this->getVar('name', null, true);
    }
    public function getFieldName()
    {
        return $this->getNameWithPrefix($this->getFieldNameWithoutPrefix());
    }
    public function fieldName($name)
    {
        return $this->vars('name', $name)->className('form-control');
    }
    public function placeholder($placeholder)
    {
        return $this->attr('placeholder', $placeholder);
    }
    public function valueDefault($value)
    {
        return $this->vars('valueDefault', $value);
    }
    public function getValueDefault()
    {
        return $this->getVar('valueDefault', null, true);
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <input {$attr} />
        HTML;
    }
    public function getValue()
    {
        $wire = $this->getWire();
        return data_get($wire, $this->getFieldName());
    }
    public function view()
    {
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $attrModel = $this->getFieldName();
        $valueDefault = $this->getValueDefault() ?? '';
        $value = $this->getValue() ?? '';
        if ($valueDefault && !$value) {
            $wire = $this->getWire();
            data_set($wire, $attrModel, $valueDefault);
        }
        $fieldView = $this->fieldView();
        if ($label = $this->getVar('label', '', true)) {
            $fieldView = <<<HTML
            <label class="form-label">{$label}</label>
            {$fieldView}
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper} x-data="sokeioField('{$attrModel}')" >
        {$fieldView}
        {$this->errorView()}
        </div>
        HTML;
    }
    public function errorView()
    {
        $errorHtml = view('sokeio::error', [
            'field' => $this->getFieldName()
        ])->render();
        if (empty($errorHtml)) {
            return '';
        }
        return <<<HTML
        <div class="field-error">
           {$errorHtml}
        </div>
        HTML;
    }
    public static function init($fieldName = null)
    {
        return (new static())->fieldName($fieldName);
    }
}
