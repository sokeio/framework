<?php

namespace Sokeio\UI\Field;


class Input extends FieldUI
{
    public function text()
    {
        return $this->attr('type', 'text');
    }

    public function password()
    {
        return $this->attr('type', 'password');
    }

    public function number()
    {
        return $this->attr('type', 'number');
    }

    public function email()
    {
        return $this->attr('type', 'email');
    }

    public function tel()
    {
        return $this->attr('type', 'tel');
    }

    public function url()
    {
        return $this->attr('type', 'url');
    }

    public function search()
    {
        return $this->attr('type', 'search');
    }

    public function date()
    {
        return $this->attr('type', 'text')->attr('wire:flatpickr');
    }

    public function time()
    {
        return $this->attr('type', 'time');
    }

    public function datetimeLocal()
    {
        return $this->attr('type', 'datetime-local');
    }

    public function week()
    {
        return $this->attr('type', 'week');
    }
    public function hidden()
    {
        return $this->attr('type', 'hidden');
    }
}
