<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\WithCol;
use Sokeio\UI\Field\Concerns\WithFieldData;
use Sokeio\UI\Field\Concerns\WithFieldInSetting;
use Sokeio\UI\Rule\WithRule;

class FieldUI extends BaseUI
{
    use WithRule, WithCol, WithFieldData, WithFieldInSetting;
    protected function classNameCol($class)
    {
        return $this->attrAdd('class', $class, 'col');
    }
    protected function initUI()
    {
        parent::initUI();
        $this->boot(function ($base) {
            $base->getManager()?->registerField($base);
        });
        $this->render(function (self $base) {
            if (!$base->containsAttr('class', 'sokeio-field-input', 'wrapper')) {
                $base->classNameWrapper('sokeio-field-input');
            }
            $debounce = $base->getVar('wire:debounce', null, true);
            if ($debounce && $debounce > 0) {
                $base->attr('wire:model.live.debounce.' . $debounce . 'ms', $base->getFieldName());
            } else {
                $base->attr('wire:model', $base->getFieldName());
            }
        });
    }

    public function label($label)
    {
        return $this->vars('label', $label);
    }
    public function classNameWrapper($className)
    {
        return $this->attrAdd('class', $className, 'wrapper');
    }

    public function getFieldNameWithoutPrefix()
    {
        return $this->getVar('name', null, true);
    }
    public function getFieldName()
    {
        return $this->getNameWithPrefix($this->getFieldNameWithoutPrefix());
    }
    public function fieldName($name): static
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
    public function disabled()
    {
        return $this->attr('disabled', 'disabled');
    }
    public function readonly()
    {
        return $this->attr('readonly', 'readonly');
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <input {$attr} />
        HTML;
    }
    public function getValue($default = null)
    {
        return $this->getValueByKey($this->getFieldNameWithoutPrefix(), $default);
    }
    public function setValue($value)
    {
        return $this->changeValue($this->getFieldNameWithoutPrefix(), $value);
    }
    public function getLabel()
    {
        return $this->getVar('label', null, true);
    }
    protected function getValueDefaultOrParam()
    {
        $valueDefault = $this->getValueDefault();
        if ($valueDefault === null) {
            $valueDefault = $this->getParams('column_' . $this->getFieldNameWithoutPrefix() . '_value');
        }
        return $valueDefault;
    }
    public function view()
    {
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $attrModel = $this->getFieldName();
        $valueDefault = $this->getValueDefaultOrParam();
        // this value is from table
        $value = $this->getValue();
        if ($valueDefault !== null && $value === null) {
            $this->setValue($valueDefault);
        }
        $fieldView = $this->fieldView();
        if ($this->checkVar('label')) {
            $label = $this->getLabel();
            $fieldView = <<<HTML
            <label class="form-label">{$label}</label>
            {$fieldView}
            HTML;
        }
        $view = <<<HTML
        <div {$attrWrapper} x-data="sokeioField('{$attrModel}')" data-sokeio-default="{$valueDefault}">
        {$fieldView}
        {$this->errorView()}
        </div>
        HTML;
        if ($this->containsAttr('class', null, 'col')) {
            $attrCol = $this->getAttr('col');
            $view = <<<HTML
            <div {$attrCol}>
            {$view}
            </div>
            HTML;
        }
        return $view;
    }
    public function errorView()
    {
        $field = $this->getFieldName();
        $errorHtml = view('sokeio::error', [
            'field' =>  $field
        ])->render();
        if (empty($errorHtml)) {
            return '';
        }
        return <<<HTML
        <div class="field-error" field-name="{$field}" >
           {$errorHtml}
        </div>
        HTML;
    }
    public static function make($fieldName = null)
    {
        return (new static())->fieldName($fieldName);
    }
}
