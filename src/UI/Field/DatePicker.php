<?php

namespace Sokeio\UI\Field;


class DatePicker extends FieldUI
{
    private $options = [];
    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    public function dateFormat($format)
    {
        return $this->options([
            'dateFormat' => $format
        ]);
    }
    public function enableTime()
    {
        return $this->options([
            'enableTime' => true,
        ]);
    }
    public function initUI()
    {
        parent::initUI();
        $this->render(function () {
            return $this->attr('type', 'text')
                ->attr('wire:flatpickr')
                ->attr('wire:flatpickr.options', json_encode($this->options));
        });
    }
}
