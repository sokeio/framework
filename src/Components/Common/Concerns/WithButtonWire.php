<?php

namespace Sokeio\Components\Common\Concerns;

trait WithButtonWire
{
    public function WireClick($WireClick): static
    {
        return $this->setKeyValue('WireClick', $WireClick);
    }
    public function getWireClick()
    {
        return $this->getValue('WireClick', null, true);
    }
    public function XClick($XClick): static
    {
        return $this->setKeyValue('XClick', $XClick);
    }
    public function getXClick()
    {
        return $this->getValue('XClick', null, true);
    }

    public function getWireAttribute()
    {
        $attr = '';
        if ($WireClick = $this->getWireClick()) {
            $attr .= ' wire:click="' . $WireClick . '" ';
        }
        if ($XClick = $this->getXClick()) {
            $attr .= ' x-on:click="' . $XClick . '" ';
        }
        return  $attr;
    }
}
