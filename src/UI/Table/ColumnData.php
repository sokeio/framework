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
}
