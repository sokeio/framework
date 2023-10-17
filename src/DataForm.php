<?php

namespace BytePlatform;

use Illuminate\Support\Concerns\Macroable;
use Livewire\Form as FormBase;

class DataForm extends FormBase implements \JsonSerializable
{
    use Macroable;
    /** @var ?BaseManager $itemManager */
    protected $itemManager;
    public function setItemManager($itemManager)
    {
        $this->itemManager = $itemManager;
        if ($this->itemManager)
            $this->itemManager->Data(function () {
                return $this;
            });
        return $this;
    }
    protected $___enableBindData = true;
    public $___checkProperty =  false;
    protected $___templateData = [];
    public function addValidationRulesToComponent()
    {
        /** @var ?BaseManager $itemManager */
        if (method_exists($this->component, 'getItemManager')) {
            $this->itemManager = $this->component->getItemManager();
            if (!!$this->itemManager && $this->___enableBindData) {
                $this->itemManager->Data(function () {
                    return $this;
                });
                if (method_exists($this->itemManager, 'getItems')) {
                    foreach ($this->itemManager->getItems() as $item) {
                        if ($item->getWhen() && !$item->getNoBindData()) {
                            $this->{$item->getField()} =  $item->getValueDefault();
                        }
                    }
                }
            }
        }

        parent::addValidationRulesToComponent();
    }
    public function FillData($data)
    {
        if ($this->itemManager && method_exists($this->itemManager, 'getItems')) {
            foreach ($this->itemManager->getItems() as $item) {
                if ($item->getWhen() && !$item->getNoBindData()) {
                    $this->{$item->getField()} = $data->{$item->getField()} ? $data->{$item->getField()} : $item->getValueDefault();
                }
            }
        } else if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    public function Clear()
    {
        $this->___templateData = [];
    }
    public function __isset($name)
    {
        return isset($this->___templateData[$name]);
    }
    public function __get($name)
    {
        return isset($this->___templateData[$name]) ? $this->___templateData[$name] : null;
    }
    public function __set($name, $value)
    {
        $this->___templateData[$name] = $value;
    }
    /**
     * Unsets an data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key)
    {
        unset($this->___templateData[$key]);
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
        $this->___templateData[$offset] = $value;
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
        return isset($this->___templateData[$offset]);
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
            unset($this->___templateData[$offset]);
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
        return $this->offsetExists($offset) ? $this->___templateData[$offset] : null;
    }
    public function __toString()
    {
        return json_encode($this->___templateData);
    }
    public function hasProperty($prop)
    {
        return isset($this->___templateData[$prop]);
    }
    public function getPropertyValue($name)
    {
        return $this->___templateData[$name];
    }
    public function jsonSerialize()
    {
        return $this->___templateData;
    }
    public function toArray()
    {
        return $this->___templateData;
    }
}
