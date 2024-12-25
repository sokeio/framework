<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\Common\Button;

class Password extends FieldUI
{
    protected function initUI()
    {
        parent::initUI();
        return $this->attr('type', 'password')->afterUI([
            Button::make()->icon('ti ti-eye')->className('btn btn-icon p-2')->attr('x-on:click', 'togglePassword(true)')->attr('x-show', '!show'),
            Button::make()->icon('ti ti-eye-off')->className('btn btn-icon p-2')->attr('x-on:click', 'togglePassword(false)')->attr('x-show', 'show'),
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
