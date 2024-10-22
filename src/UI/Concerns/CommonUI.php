<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\Support\AlpineUI;
use Sokeio\UI\Support\DataUI;

trait CommonUI
{
    private DataUI|null $data;
    private AlpineUI|null  $alpine;

    public function initCommonUI()
    {
        $this->data = DataUI::create($this);
        $this->alpine = new AlpineUI($this);
    }
    protected function data($group): DataUI
    {
        return $this->data->group($group);
    }
    public function x()
    {
        return $this->alpine;
    }
    public function className($className)
    {
        return $this->attrAdd('class', $className);
    }
    public function confirm($message)
    {
        return $this->attr('wire:confirm', $message);
    }
    public function id($id): self
    {
        return $this->attr('id', $id);
    }
    public function getId()
    {
        return $this->getAttrKey('id');
    }
    public function vars($key, $value = null): static
    {
        return $this->data('vars')->set($key, $value);
    }
    public function getVar($key, $default = null, $isText = false)
    {
        return $this->data('vars')->get($key, $default, $isText);
    }
    protected function getAttributeByGroup($group = 'default')
    {
        return $this->data('attributes_' . $group);
    }
    public function attr($key, $value = null, $group = 'default'): static
    {
        return $this->getAttributeByGroup($group)->set($key, $value);
    }
    public function attrAdd($key, $value = null, $group = 'default'): static
    {
        return $this->getAttributeByGroup($group)->append($key, $value);
    }
    protected function getAttrKey($key, $default = null, $group = 'default', $isText = false)
    {
        return $this->getAttributeByGroup($group)->get($key, $default, $isText);
    }
    public function checkAttr($key, $value = null, $group = 'default')
    {
        $attrValue = $this->getAttrKey($key, null, $group, true);
        return $attrValue !== null && $attrValue === $value;
    }
    public function containsAttr($key, $value = null, $group = 'default')
    {
        $attrValue = $this->getAttrKey($key, null, $group, true);
        return $attrValue !== null && str($attrValue)->contains($value);
    }
    protected function getAttr($group = 'default')
    {
        return $this->getAttributeByGroup($group)->getAttributeText();
    }
}
