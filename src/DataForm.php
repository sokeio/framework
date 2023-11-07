<?php

namespace BytePlatform;

use BytePlatform\Form as FormBase;

class DataForm extends FormBase
{
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
    public function addValidationRulesToComponent()
    {
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
}
