<?php

namespace Sokeio\UI\Field;

use Carbon\Carbon;

class DatePicker extends FieldUI
{
    private $options = [
        'dateFormat' => 'Y-m-d H:i:s',
        'enableTime' => false
    ];
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
        $this->render(function (self $base) {
            $value = $base->getValue();
            if ($value) {
                $base->setValue(Carbon::parse($value)->format($base->options['dateFormat']));
            }
            return $base->attr('type', 'text')
                ->attr('wire:flatpickr')
                ->attr('wire:flatpickr.options', json_encode($base->options));
        });
    }
}
