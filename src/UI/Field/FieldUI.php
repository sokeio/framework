<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;

class FieldUI extends BaseUI
{
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            if (!$this->containsAttr('class', 'sokeio-field-input', 'wrapper')) {
                $this->classNameWrapper('sokeio-field-input');
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
    protected function getNameWithPrefix($name)
    {
        return $this->getPrefix() ? $this->getPrefix() . '.' . $name : $name;
    }
    public function fieldName($name)
    {
        return $this->vars('name', $name)->render(function () use ($name) {
            $this->attr('wire:model', $this->getNameWithPrefix($name))
                ->className('form-control');
        });
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
