<?php

namespace Sokeio\Components\Common\Concerns;

trait WithButtonWire
{
    public function wireClick($wireClick): static
    {
        return $this->setKeyValue('wireClick', $wireClick);
    }
    public function getWireClick()
    {
        return $this->getValue('wireClick', null, true);
    }
    public function xClick($xClick): static
    {
        return $this->setKeyValue('xClick', $xClick);
    }
    public function getXClick()
    {
        return $this->getValue('xClick', null, true);
    }

    public function getWireAttribute()
    {
        $attr = '';
        if ($wireClick = $this->getWireClick()) {
            $attr .= ' wire:click="' . $wireClick . '" ';
        }
        if ($xClick = $this->getXClick()) {
            $attr .= ' x-on:click="' . $xClick . '" ';
        }
        return  $attr;
    }
}
