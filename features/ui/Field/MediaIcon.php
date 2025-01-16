<?php

namespace Sokeio\UI\Field;


class MediaIcon extends FieldUI
{

    public function buttonText($buttonText)
    {
        return $this->vars('buttonText', $buttonText);
    }
    public function buttonClass($buttonClass)
    {
        return $this->vars('buttonClass', $buttonClass);
    }

    protected function fieldView()
    {
        $field = $this->getFieldName();
        $buttonText = $this->getVar('buttonText', 'Icon', true);
        $buttonClass = $this->getVar('buttonClass', 'btn-primary', true);

        return <<<HTML
            <div class="d-flex g-1">
                <i class="fs-1" x-show="FieldValue" x-bind:class="FieldValue"></i>
                <input type="text" class="form-control mx-1" x-model="FieldValue" placeholder="{$buttonText}">
                <button type="button" class="btn {$buttonClass}" wire:media-icon="{$field}">
                    {$buttonText}
                </button>
            </div>
        HTML;
    }
}
