<?php

namespace Sokeio\Admin\Components\Field\Concerns;

trait WithFieldWire
{

    public function getWireAttribute()
    {
        $attr = 'wire:model';
        $attr .= ($this->wireModelArr[$this->wireModelType]);
        switch ($this->wireModelType) {
            case 3: //debounce
                $attr .= '.' . $this->wireModelDebounce;
                break;
            case 4: //throttle
                $attr .= '.' . $this->wireModelThrottle;
                break;
        }
        $attr .= '="' .  $this->getFormField() . '" ';
        if ($this->getDisable()) {
            $attr .= ' disabled ';
        }
        return $attr;
    }
    /*
    0-default
    1-live
    2-blur
    3-debounce
    4-throttle
    */
    private $wireModelArr = ['', '.live', '.blur', '.live.debounce', '.live.throttle'];
    private $wireModelType = 0;
    private $wireModelDebounce = '150ms';
    private $wireModelThrottle = '150ms';
    public function WireLive(): static
    {
        $this->wireModelType = 1;
        return $this;
    }
    public function WireBlur(): static
    {
        $this->wireModelType = 2;
        return $this;
    }
    public function WireLiveDebounce($value = '150ms'): static
    {
        $this->wireModelType = 3;
        $this->wireModelDebounce = $value;
        return $this;
    }
    public function WireLiveThrottle($value = '150ms'): static
    {
        $this->wireModelType = 4;
        $this->wireModelThrottle = $value;
        return $this;
    }
}
