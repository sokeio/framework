<?php

namespace Sokeio\UI\Field;


class Input extends FieldUI
{
    public function text()
    {
        return $this->attr('type', 'text');
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
 
    public function hidden()
    {
        return $this->attr('type', 'hidden');
    }
}
