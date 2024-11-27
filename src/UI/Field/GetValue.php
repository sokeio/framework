<?php

namespace Sokeio\UI\Field;


class GetValue extends FieldUI
{
    public function showDebug()
    {
        return $this->vars('showDebug', true);
    }
    public function fieldRef($field)
    {
        return $this->vars('fieldRef', $field);
    }
    public function componetRef($component)
    {
        return $this->vars('componentRef', $component);
    }
    public function view()
    {
        $showDebug = $this->getVar('showDebug', false, true);
        $attrModel = $this->getFieldName();
        $valueDefault = $this->getValueDefault();
        $value = $this->getValue();
        if ($valueDefault !== null && $value === null) {
            $wire = $this->getWire();
            data_set($wire, $attrModel, $valueDefault);
        }
        $fieldRef = $this->getVar('fieldRef', '', true);
        $componentRef = $this->getVar('componentRef', '', true);
        if ($showDebug) {
            return <<<HTML
            <div
            class="p-1 border border-dashed border-gray-300 bg-blue-lt rounded"
            x-data="sokeioField('{$attrModel}')"
            wire:get-value="{$fieldRef}"
            wire:get-value.model="{$attrModel}"
            wire:get-value.parent="{$componentRef}"
            >
            <span class="fw-bold">Debug</span><br>
            <span>fieldRef:</span>
            <span>{$fieldRef}</span><br>

            <span>{$attrModel}:</span>
            <span x-text="FieldValue"></span>
            </div>
            HTML;
        }
        return <<<HTML
        <div style="display: none;"
        x-data="sokeioField('{$attrModel}')"
        wire:get-value="{$fieldRef}"
        wire:get-value.model="{$attrModel}"
        wire:get-value.parent="{$componentRef}"
        >
        </div>
        HTML;
    }
}
