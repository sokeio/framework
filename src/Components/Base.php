<?php

namespace Sokeio\Admin\Components;

use Sokeio\Laravel\BaseCallback;

class Base extends BaseCallback
{
    public function boot()
    {
    }
    protected function __construct($value)
    {
    }
    public static function Make($value)
    {
        return (new static($value));
    }
    public function When($When)
    {
        return $this->setKeyValue('When', $When);
    }
    public function getWhen()
    {
        return $this->getValue('When', true);
    }
    public function Attribute($Attribute)
    {
        return $this->setKeyValue('Attribute', $Attribute);
    }
    public function getAttribute()
    {
        return $this->getValue('Attribute');
    }

    public function ClassName($ClassName)
    {
        return $this->setKeyValue('ClassName', $ClassName);
    }
    public function getClassName()
    {
        return $this->getValue('ClassName');
    }
    public function checkPrex()
    {
        return $this->checkKey('Prex');
    }
    private $dataItem = null;
    public function DataItem($value)
    {
        $this->dataItem = $value;
        return $this;
    }
    public function getDataItem()
    {
        return  $this->dataItem;
    }
    public function Prex($Prex)
    {
        return $this->setKeyValue('Prex', $Prex, true);
    }
    public function getPrex()
    {
        return $this->getValue('Prex');
    }
    public function getView()
    {
    }
}
