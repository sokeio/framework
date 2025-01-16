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
            if (!$base->containsAttrWrapper('class', 'sokeio-field-input')) {
                $base->classNameWrapper('sokeio-field-input');
            }
            $name = $base->getFieldNameWithoutPrefix();
            if ($name) {
                $name = $base->getFieldName();
                $debounce = $base->getVar('wire:debounce', null, true);
                if ($debounce && $debounce > 0) {
                    $base->attr('wire:model.live.debounce.' . $debounce . 'ms', $name);
                } else {
                    $base->attr('wire:model', $name);
                }
            }
        });
    }
    public function containsAttrWrapper($key, $value = null)
    {
        return $this->containsAttr($key, $value, 'wrapper');
    }
    public function attrWrapper($key, $value)
    {
        return $this->attr($key, $value, 'wrapper');
    }
    public function attrAddWrapper($key, $value)
    {
        return $this->attrAdd($key, $value, 'wrapper');
    }
    public function classNameWrapper($className)
    {
        return $this->attrAddWrapper('class', $className);
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
    public function showCount()
    {
        return $this->vars('showCount', true);
    }
    public function hideCount()
    {
        return $this->vars('showCount', false);
    }
    public function maxLength($maxLength)
    {
        return $this->attr('maxlength', $maxLength)->vars('maxLength', $maxLength);
    }
    private function labelCount()
    {
        if ($this->getVar('showCount', false, true)) {

            if ($maxLength = $this->getVar('maxLength', null, true)) {
                return '<span class="form-label-description" x-text="FieldValue?.length + \'/\' + ' . $maxLength . '"></span>';
            }
            return '<span class="form-label-description" x-text="FieldValue?.length"></span>';
        }
        return '';
    }
    public function beforeIcon($icon)
    {
        return $this->vars('beforeIcon', $icon);
    }
    public function afterIcon($icon)
    {
        return $this->vars('afterIcon', $icon);
    }
    public function beforeUI($ui)
    {
        return $this->child($ui, 'beforeUI');
    }
    public function afterUI($ui)
    {
        return $this->child($ui, 'afterUI');
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
        if ($beforeIcon = $this->getVar('beforeIcon', '', true) || $afterIcon = $this->getVar('afterIcon', '', true)) {
            if ($beforeIcon) {
                $beforeIcon = '<span class="input-icon-addon"><i class="' . $beforeIcon . '"></i></span>';
            }
            if ($afterIcon) {
                $afterIcon = '<span class="input-icon-addon"><i class="' . $afterIcon . '"></i></span>';
            }
            $fieldView = <<<HTML
            <div class="input-icon">
                {$beforeIcon}
                {$fieldView}
                {$afterIcon}
            </div>
            HTML;
        }
        if ($this->hasChilds('beforeUI') || $this->hasChilds('afterUI')) {
            $fieldView = <<<HTML
            <div class="input-group">
                {$this->renderChilds('beforeUI')}
                {$fieldView}
                {$this->renderChilds('afterUI')}
            </div>
            HTML;
        }
        $fieldLabel = '';
        if ($this->checkVar('label')) {
            $label = $this->getLabel();
            $fieldLabel = <<<HTML
            <label class="form-label">{$label} {$this->labelCount()}</label>
            HTML;
        }
        $view = <<<HTML
        <div {$attrWrapper} x-data="sokeioField('{$attrModel}')" data-sokeio-default="{$valueDefault}">
        {$fieldLabel}
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
