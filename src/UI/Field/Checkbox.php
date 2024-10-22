<?php

namespace Sokeio\UI\Field;


class Checkbox extends FieldUI
{
    public function labelCheckbox($label)
    {
        return $this->render(function () use ($label) {
            $this->vars('labelCheckbox', $label);
        });
    }
    protected function initUI()
    {
        $this->render(function () {
            if (!$this->containsAttr('class', 'form-check-input')) {
                $this->className('form-check-input');
            }
            $this->attr('type', 'checkbox');
        });
    }
    public function view()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $labelCheckbox = $this->getVar('labelCheckbox', '', true);
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <div {$attrWrapper}>
                <label class="form-label">{$label}</label>
                <label class="form-check">
                    <input {$attr} >
                    <span class="form-check-label">{$labelCheckbox}</span>
                </label>
            </div>
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper}>
            <label class="form-check">
                <input {$attr} >
                <span class="form-check-label">{$labelCheckbox}</span>
            </label>
        </div>
        HTML;
    }
}
