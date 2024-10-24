<?php

namespace Sokeio\UI\Field;


class Textarea extends FieldUI
{
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <div {$attrWrapper}>
                <label class="form-label">{$label}</label>
                <textarea {$attr}></textarea>
            </div>
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper}>
            <textarea {$attr}></textarea>
        </div>
        HTML;
    }
}
