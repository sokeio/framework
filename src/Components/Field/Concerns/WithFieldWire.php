<?php

namespace Sokeio\Components\Field\Concerns;

trait WithFieldWire
{

    public function getWireAttribute()
    {
        $attribute = 'wire:model' . $this->wireModelArr[$this->wireModelType];
        if ($this->wireModelType === 3) { //debounce
            $attribute .= '.' . $this->wireModelDebounce;
        } elseif ($this->wireModelType === 4) { //throttle
            $attribute .= '.' . $this->wireModelThrottle;
        }
        $attribute .= '="' .  $this->getFormField() . '" ';

        if ($this->getDisable()) {
            $attribute .= ' disabled ';
        }

        if ($this->wireGetValueByKey) {
            $attribute .= ' wire:get-value="' . $this->wireGetValueByKey . '" ';

            if ($this->wireGetValueByParent) {
                $attribute .= ' wire:get-value-parent="' . $this->wireGetValueByParent .  '" ';
            }
        }

        if ($additionalInput = $this->getAttributeInput()) {
            $attribute .= $additionalInput;
        }

        return $attribute;
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
    private $wireModelDebounce = '151ms';
    private $wireModelThrottle = '152ms';
    private $wireGetValueByKey;
    private $wireGetValueByParent = null;
    public function wireLive(): static
    {
        $this->wireModelType = 1;
        return $this;
    }
    public function wireBlur(): static
    {
        $this->wireModelType = 2;
        return $this;
    }
    public function wireLiveDebounce($value = '150ms'): static
    {
        $this->wireModelType = 3;
        $this->wireModelDebounce = $value;
        return $this;
    }
    public function wireLiveThrottle($value = '150ms'): static
    {
        $this->wireModelType = 4;
        $this->wireModelThrottle = $value;
        return $this;
    }
    public function wireGetValue($key, $parentId = null): static
    {
        $this->wireGetValueByKey    = $key;
        $this->wireGetValueByParent = $parentId;
        return $this;
    }
}
