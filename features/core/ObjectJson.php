<?php

namespace Sokeio\Core;

use Illuminate\Support\Str;

class ObjectJson implements \ArrayAccess, \JsonSerializable
{
    private $data;
    public static function create($data = null): self
    {
        return new self($data);
    }
    public function CloneData()
    {
        return static::create(json_decode(json_encode($this->data ?? []), true));
    }
    public function __construct($data = null)
    {
        $this->data = $data ?? [];
    }
    public function getData()
    {
        return $this->data;
    }
    public function setData($data)
    {
        return $this->data = $data ?? [];
    }
    private function getDataByKey($key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }
        if (method_exists($this, 'get' . Str::studly($key) . 'Data')) {
            return $this->{'get' . Str::studly($key) . 'Data'}();
        }
        return data_get($this->data, $key);
    }
    private function setDataByKey($key, $value)
    {
        if (is_array($value)) {
            $value = static::create($value);
        }
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
            return;
        }
        if (method_exists($this, 'set' . Str::studly($key) . 'Data')) {
            $this->{'set' . Str::studly($key) . 'Data'}($value);
            return;
        }
        $this->data[$key] = $value;
    }
    private function unsetDataByKey($key)
    {
        if (property_exists($this, $key)) {
            unset($this->{$key});
            return;
        }
        if (method_exists($this, 'unset' . Str::studly($key) . 'Data')) {
            $this->{'unset' . Str::studly($key) . 'Data'}();
            return;
        }
        unset($this->data[$key]);
    }
    private function checkKey($key)
    {
        if (property_exists($this, $key)) {
            return true;
        }
        if (method_exists($this, 'get' . Str::studly($key) . 'Data')) {
            return true;
        }
        return isset($this->data[$key]);
    }
    /**
     * Get a data by key
     *
     * @param string The key data to retrieve
     * @access public
     */
    public function __get($key)
    {
        return $this->getDataByKey($key);
    }

    /**
     * Assigns a value to the specified data
     *
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     * @access public
     */
    public function __set($key, $value)
    {
        $this->setDataByKey($key, $value);
    }

    /**
     * Whether or not an data exists by key
     *
     * @param string An data key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset($key)
    {
        return $this->checkKey($key);
    }

    /**
     * Unsets an data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key)
    {
        $this->unsetDataByKey($key);
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     * @access public
     */
    public function offsetSet($offset, $value)
    {
        $this->setDataByKey($offset, $value);
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
        return $this->checkKey($offset);
    }

    /**
     * Unsets an offset
     *
     * @param string The offset to unset
     * @access public
     */
    public function offsetUnset($offset)
    {
        $this->unsetDataByKey($offset);
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
        return $this->getDataByKey($offset);
    }
    public function __toString()
    {
        return json_encode($this->data);
    }
    public function getValue($key, $default = '')
    {
        return data_get($this->data, $key, $default);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
