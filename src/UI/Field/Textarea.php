<?php

namespace Sokeio\UI\Field;


class Textarea extends FieldUI
{
    protected function fieldView()
    {
        $attr = $this->getAttr();
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
             <label class="form-label">{$label}</label>
             <textarea {$attr}></textarea>
            HTML;
        }
        return <<<HTML
         <textarea {$attr}></textarea>
        HTML;
    }
}
