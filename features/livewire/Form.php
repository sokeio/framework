<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Livewire\Support\SupportFormObjects\Form as SupportFormObjectsForm;

class Form extends SupportFormObjectsForm implements \JsonSerializable
{
    use Macroable;
    protected $soTemplateData = [];

    public function clear()
    {
        $this->soTemplateData = [];
    }
    public function fill($values)
    {
        if (!$values) {
            return;
        }

        if (method_exists($values, 'toArray')) {
            $values = $values->toArray();
        }

        foreach ($values as $key => $value) {
            data_set($this->soTemplateData, $key, $value);
        }
    }
    public function parseModel(&$data, $fill = null)
    {
        if (!$data) {
            return;
        }
        foreach ($fill as $key) {
            data_set($data, $key, data_get($this->soTemplateData, $key));
        }
    }
    public function __isset($name)
    {
        return isset($this->soTemplateData[$name]);
    }
    public function __get($name)
    {
        return isset($this->soTemplateData[$name]) ? $this->soTemplateData[$name] : null;
    }
    public function __set($name, $value)
    {
        data_set($this->soTemplateData, $name, $value);
    }
    /**
     * Unsets an data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key)
    {
        unset($this->soTemplateData[$key]);
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset,  $value)
    {
        data_set($this->soTemplateData, $offset, $value);
    }

    /**
     * Whether or not an offset exists
     *
     * @param string An offset to check for
     * @access public
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->soTemplateData[$offset]);
    }

    /**
     * Unsets an offset
     *
     * @param string The offset to unset
     * @access public
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->soTemplateData[$offset]);
        }
    }
    /**
     * Returns the value at specified offset
     *
     * @param string The offset to retrieve
     * @access public
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->soTemplateData[$offset] : null;
    }
    public function __toString()
    {
        return json_encode($this->soTemplateData);
    }
    public function hasProperty($prop)
    {
        return isset($this->soTemplateData[$prop]);
    }
    public function getPropertyValue($name)
    {
        if ($this->hasProperty($name)) {
            return $this->soTemplateData[$name];
        }
        return null;
    }
    public function jsonSerialize(): mixed
    {
        return $this->soTemplateData;
    }
    public function keys()
    {
        return array_keys($this->soTemplateData);
    }
    public function toArray()
    {
        return $this->soTemplateData;
    }
}
