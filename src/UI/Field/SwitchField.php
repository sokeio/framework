<?php

namespace Sokeio\UI\Field;


class SwitchField extends FieldUI
{
    public function labelTrue($label)
    {
        return $this->render(function () use ($label) {
            $this->vars('labelTrue', $label);
        });
    }
    public function labelFalse($label)
    {
        return $this->render(function () use ($label) {
            $this->vars('labelFalse', $label);
        });
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            if (!$this->containsAttr('class', 'form-check-input')) {
                $this->className('form-check-input');
            }
            $this->attr('type', 'checkbox');
            if (!$this->containsAttr('value')) {
                $this->attr('value', '1');
            }
            if ($this->getValueDefault()) {
                $this->attr('checked', 'checked');
            }
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $labelTrue = $this->getVar('labelTrue', 'Active', true);
        $labelFalse = $this->getVar('labelFalse', 'Unactive', true);
        return <<<HTML
        <div {$attrWrapper}>
            <label class="form-check form-switch">
                <input {$attr} >
                <span class="form-check-label" x-show="FieldValue" style="display: none;">{$labelTrue}</span>
                <span class="form-check-label" x-show="!FieldValue" style="display: none;">{$labelFalse}</span>
            </label>
        </div>
        HTML;
    }
}
