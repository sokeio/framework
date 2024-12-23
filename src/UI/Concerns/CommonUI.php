<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\Support\AlpineUI;
use Sokeio\UI\Support\DataUI;

trait CommonUI
{
    use DataShareUI;
    private DataUI|null $data;
    private AlpineUI|null  $alpine;
    private $dataHooks = [];
    private $applyFlg = false;
    protected function registerData($callback)
    {
        if ($this->applyFlg) {
            $callback($this);
        } else {
            $this->dataHooks[] = $callback;
        }
        return $this;
    }
    public function applyRegisterData()
    {
        foreach ($this->dataHooks as $callback) {
            $callback($this);
        }
        $this->dataHooks = [];
        $this->applyFlg = true;
        return $this;
    }
    public function initCommonUI()
    {
        $this->data = DataUI::create($this);
        $this->alpine = new AlpineUI($this);
    }
    public function data($group): DataUI
    {
        return $this->data->group($group);
    }
    public function x()
    {
        return $this->alpine;
    }
    public function xData($data)
    {
        return $this->alpine->data($data);
    }
    public function xShow($data)
    {
        return $this->alpine->show($data);
    }
    public function xBind($data)
    {
        return $this->alpine->bind($data);
    }
    public function xModel($data)
    {
        return $this->alpine->model($data);
    }
    public function className($className): static
    {
        return $this->attrAdd('class', $className);
    }
    public function confirm($message): static
    {
        return $this->attr('wire:confirm', $message);
    }
    public function id($id): static
    {
        return $this->attr('id', $id);
    }
    public function getId()
    {
        return $this->getAttrKey('id');
    }
    public function wireKey($callback): static
    {
        return $this->attr('wire:key', $callback);
    }
    public function vars($key, $value = null): static
    {
        return $this->registerData(function ($base) use ($key, $value) {
            $base->data('vars')->set($key, $value);
        });
    }
    public function getVar($key, $default = null, $isText = false, $separator = ' ')
    {
        return $this->data('vars')->get($key, $default, $isText, $separator);
    }
    public function getAttributeByGroup($group = 'default')
    {
        return $this->data('attributes_' . $group);
    }
    public function attr($key, $value = null, $group = 'default'): static
    {
        return $this->registerData(function ($base) use ($key, $value, $group) {
            $base->getAttributeByGroup($group)->set($key, $value);
        });
    }
    public function removeAttr($key, $value = null, $group = 'default'): static
    {
        return $this->registerData(function ($base) use ($key, $value, $group) {
            $base->getAttributeByGroup($group)->remove($key, $value);
        });
    }
    public function attrAdd($key, $value = null, $group = 'default'): static
    {
        return $this->registerData(function ($base) use ($key, $value, $group) {
            $base->getAttributeByGroup($group)->append($key, $value);
        });
    }
    public function getAttrKey($key, $default = null, $group = 'default', $isText = false, $separator = ' ')
    {
        return $this->getAttributeByGroup($group)->get($key, $default, $isText, $separator);
    }
    public function checkAttr($key, $value = null, $group = 'default')
    {
        $attrValue = $this->getAttrKey($key, null, $group, true);
        return $attrValue !== null && $attrValue === $value;
    }
    public function checkVar($key, $value = null, $group = 'default')
    {
        $attrValue = $this->getVar($key, null, $group, true);
        return $attrValue !== null && ($value === null || str($attrValue)->contains($value));
    }
    public function containsAttr($key, $value = null, $group = 'default')
    {
        $attrValue = $this->getAttrKey($key, null, $group, true);
        return $attrValue !== null && ($value === null || str($attrValue)->contains($value));
    }
    public function getAttr($group = 'default', $checkFn = null)
    {
        return $this->getAttributeByGroup($group)->getAttributeText($checkFn);
    }
    public function getAttrValue($group = 'default')
    {
        return $this->getAttributeByGroup($group)->getData();
    }
}
