<?php

namespace Sokeio\UI\Field;


class ColorPicker extends FieldUI
{
    private $options = [];
    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    public function initUI()
    {
        parent::initUI();
        $this->render(function (self $base) {
            return $base->attr('type', 'text')
                ->attr('wire:coloris')
                ->attr('wire:coloris.options', json_encode($base->options));
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <div wire:ignore>
            <input {$attr} />
        </div>
        HTML;
    }
}
