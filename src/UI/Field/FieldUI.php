<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;

class FieldUI extends BaseUI
{
    public function label($label)
    {
        return $this->vars('label', $label);
    }
    public function classNameWrapper($className)
    {
        return $this->attrAdd('class', $className, 'wrapper');
    }
    public function fieldName($name)
    {
        return $this->attr('wire:model', $name)->vars('name', $name)->className('form-control');
    }
    public function view()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <div {$attrWrapper}>
                <label class="form-label">{$label}</label>
                <input {$attr} />
            </div>
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper}>
            <input {$attr} />
        </div>
        HTML;
    }
    public static function init($fieldName = null)
    {
        return (new static())->fieldName($fieldName);
    }
}
