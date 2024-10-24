<?php

namespace Sokeio\UI\Field;


class DatePicker extends FieldUI
{
    public function initUI()
    {
        parent::initUI();
        $this->render(function () {
            return $this->attr('type', 'text')->attr('wire:flatpickr');
        });
    }
}
