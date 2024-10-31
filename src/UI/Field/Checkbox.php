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
        parent::initUI();
        $this->render(function () {
            if (!$this->containsAttr('class', 'form-check-input')) {
                $this->className('form-check-input');
            }
            $this->attr('type', 'checkbox');
            if ($this->containsAttr('value')) {
                $this->attr('value', '1');
            }
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $labelCheckbox = $this->getVar('labelCheckbox', '', true);
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
