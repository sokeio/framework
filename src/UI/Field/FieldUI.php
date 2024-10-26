<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;

class FieldUI extends BaseUI
{
    protected function initUI()
    {
        parent::initUI();
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
    public function debounce($debounce = 250)
    {
        return $this->vars('wire:debounce', $debounce);
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
    public function getFieldName()
    {
        return $this->getNameWithPrefix($this->getVar('name', null, true));
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
        return $this->getVar('valueDefault', 'null', true);
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <label class="form-label">{$label}</label>
            <input {$attr} />
            HTML;
        }
        return <<<HTML
        <input {$attr} />
        HTML;
    }
    public function view()
    {
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $attrModel = $this->getFieldName();
        $valueDefault = $this->getValueDefault();
        return <<<HTML
        <div {$attrWrapper} x-data="{
            get FieldValue(){ return \$wire.{$attrModel}; },
            set FieldValue(value){\$wire.{$attrModel} = value;}
        }" x-init="if(!FieldValue){FieldValue = {$valueDefault} }">
        {$this->fieldView()}
        {$this->errorView()}
        </div>
        HTML;
    }
    public function errorView()
    {
        return <<<HTML
        <div class="invalid-feedback">
            <span x-text="FieldValue"></span>
        </div>
        HTML;
    }
    public static function init($fieldName = null)
    {
        return (new static())->fieldName($fieldName);
    }
}
