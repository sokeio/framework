<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\Common\Button;

class Password extends FieldUI
{
    protected function initUI()
    {
        parent::initUI();
        return $this->attr('type', 'password')->afterIcon('ti ti-eye')->afterUI([
            Button::make()->icon('ti ti-eye')->className('btn btn-icon')->attr('x-on:click', 'togglePassword(true)'),
            Button::make()->icon('ti ti-eye-off')->className('btn btn-icon')->attr('x-on:click', 'togglePassword(false)'),
        ])->attrWrapper('x-data', '{
            show: false,
            togglePassword: function (show) {
                this.show = show;
            }
        }')->attr('x-bind:type', 'show ? "text" : "password"');
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <input {$attr} />
        HTML;
    }
}
