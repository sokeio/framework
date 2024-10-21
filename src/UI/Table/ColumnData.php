<?php

namespace Sokeio\UI\Table;

trait ColumnData
{
    protected $data = [];
    protected function setData($key, $data)
    {
        $this->data[$key] = $data;
        return $this;
    }
    public function getData($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    public function setField($field)
    {
        return $this->setData('field', $field);
    }
    public function disableSort()
    {
        return $this->setData('disableSort', true);
    }
    public function enableSort()
    {
        return $this->setData('disableSort', false);
    }
    public function getDisableSort($default = null)
    {
        return $this->getData('disableSort', $default);
    }
    public function getField($default = null)
    {
        return $this->getData('field', $default);
    }
    public function setLabel($label)
    {
        return $this->setData('label', $label);
    }
    public function getLabel($default = null)
    {
        return $this->getData('label', $default);
    }
    public function renderHeader($callback)
    {
        return $this->setData('renderHeader', $callback);
    }
    public function getRenderHeader($default = null)
    {
        return $this->getData('renderHeader', $default);
    }
    public function renderCell($callback)
    {
        return $this->setData('renderCell', $callback);
    }
    public function getRenderCell($default = null)
    {
        return $this->getData('renderCell', $default);
    }
    public function classNameHeader($callback)
    {
        return $this->setData('classNameHeader', $callback);
    }
    public function getClassNameHeader($default = null)
    {
        return $this->getData('classNameHeader', $default);
    }
    public function classNameCell($callback)
    {
        return $this->setData('classNameCell', $callback);
    }
    public function getClassNameCell($default = null)
    {
        return $this->getData('classNameCell', $default);
    }
}
