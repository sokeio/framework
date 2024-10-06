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
    public function attr($key, $value = null): static
    {
        return $this->data('attributes')->set($key, $value);
    }
    public function attrAdd($key, $value = null): static
    {
        return $this->data('attributes')->append($key, $value);
    }
    protected function getAttrKey($key, $default = null)
    {
        return $this->data('attributes')->get($key, $default);
    }
    protected function getAttr()
    {
        return $this->data('attributes')->getAttributeText();
    }
}
