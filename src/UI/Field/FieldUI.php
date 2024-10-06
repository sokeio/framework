<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;

class FieldUI extends BaseUI
{
    public function label($label)
    {
        return $this->vars('label', $label);
    }
    public function fieldName($name)
    {
        return $this->attr('wire:model', $name)->vars('name', $name)->className('form-control');
    }
    public function view()
    {
        $attr = $this->getAttr();
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <div class="mb-3">
                <label class="form-label">{$label}</label>
                <input {$attr} />
            </div>
            HTML;
        }
        return <<<HTML
        <input {$attr} />
        HTML;
    }
}
