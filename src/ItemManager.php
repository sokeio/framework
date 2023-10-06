<?php

namespace BytePlatform;


class ItemManager extends BaseManager
{
    private $__istable = false;
    private $__methodType = 'get';

    /** @var Item[] $__item */
    private  $__formSearch = [];
    /** @var Item[] $__item */
    private  $__item = [];
    private function __construct($__istable)
    {
        parent::__construct();
        $this->__istable = $__istable;
        $this->OrderAt(0);
        if ($this->IsTable()) {
            $this->Manager($this)
                ->Filter(function () {
                    return false;
                })
                ->Sort(function () {
                    return false;
                })
                ->EditInTable(function () {
                    return false;
                });
        } else {
            $this->useMethodPost()->Manager($this)->FormDoSave(function ($params, $component, $manager) {
                $component->form->DataFromForm();
                $component->showMessage($manager->getMessage());
                $component->closeComponent();
                $component->refreshRefComponent();
            })->NoBindData(function () {
                return false;
            })->ButtonSaveText(function () {
                return "Save";
            });
        }
    }
    public function useMethodPost()
    {
        $this->__methodType = 'post';
        return $this;
    }
    public function useMethodGet()
    {
        $this->__methodType = 'get';
        return $this;
    }
    public function getMethodType()
    {
        return $this->__methodType;
    }

    public function FormDoSave($callback)
    {
        return $this->Action('FORM_DO_SAVE', $callback);
    }
    public function ButtonSaveText($buttonSaveText)
    {
        return $this->setKeyValue('buttonSaveText', $buttonSaveText);
    }
    public function getButtonSaveText()
    {
        return $this->getValue('buttonSaveText');
    }
    public function IsTable()
    {
        return $this->__istable;
    }

    public function CheckBoxRow($checkBoxRow = null)
    {
        if ($checkBoxRow == null) {
            $checkBoxRow = function () {
                return true;
            };
        }
        return $this->setKeyValue('checkBoxRow', $checkBoxRow);
    }
    public function getCheckBoxRow()
    {
        return $this->getValue('checkBoxRow');
    }
    public function ButtonOnPage($buttonOnPage)
    {
        return $this->setKeyValue('buttonOnPage', $buttonOnPage);
    }
    public function getButtonOnPage()
    {
        return $this->getValue('buttonOnPage');
    }
    public function ButtonInAction($buttonInAction)
    {
        return $this->setKeyValue('buttonInAction', $buttonInAction);
    }
    public function getButtonInAction()
    {
        return $this->getValue('buttonInAction');
    }
    public function ButtonInTable($buttonInTable)
    {
        return $this->setKeyValue('buttonInTable', $buttonInTable);
    }
    public function getButtonInTable()
    {
        return $this->getValue('buttonInTable');
    }
    public function Button($button)
    {
        return $this->setKeyValue('button', $button);
    }
    public function geButton()
    {
        return $this->getValue('button');
    }
    public function Message($message)
    {
        return $this->setKeyValue('message', $message);
    }
    public function getMessage()
    {
        return $this->getValue('message');
    }

    public function OrderAt($orderAt)
    {
        return $this->setKeyValue('orderAt', $orderAt);
    }
    public function getOrderAt()
    {
        return $this->getValue('orderAt');
    }
    public function Item($item)
    {
        if (!$item) return $this;
        if (is_array($item)) {
            foreach ($item as $_item) {
                $this->Item($_item);
            }
            return $this;
        }
        if (is_callable($item)) {
            $item = $item($this);
        }
        if (is_string($item)) {
            $item = app($item);
        }
        $this->__item[] = $item->Manager($this);
        return $this;
    }
    public function FormSearch($item)
    {
        if (!$item) return $this;
        if (is_array($item)) {
            foreach ($item as $_item) {
                $this->FormSearch($_item);
            }
            return $this;
        }
        if (is_callable($item)) {
            $item = $item($this);
        }
        if (is_string($item)) {
            $item = app($item);
        }
        $this->__formSearch[] = $item->Manager($this);
        return $this;
    }
    public function getFormSearch()
    {
        if (!$this->__formSearch || count($this->__formSearch) == 0) return null;
        return self::Form()->ModelForm(function () {
            return "formSearch.ets.";
        })->Item($this->__formSearch)->Filter();
    }
    public function Filter($filter = null)
    {
        if ($filter == null) {
            $filter = function () {
                return true;
            };
        }
        return $this->setKeyValue('filter', $filter);
    }

    public function getFilter()
    {
        return $this->getValue('filter');
    }

    public function Sort($sort = null)
    {
        if ($sort == null) {
            $sort = function () {
                return true;
            };
        }
        return $this->setKeyValue('sort', $sort);
    }

    public function getSort()
    {
        return $this->getValue('sort');
    }
    /** @return Item[] $items */
    public function getItems()
    {
        return $this->__item;
    }
    public function getDataId()
    {
        return $this->getData()->getDataId();
    }
    public static function Table()
    {
        return new self(true);
    }
    public static function Form()
    {
        return new self(false);
    }
}
