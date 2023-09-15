<?php

namespace BytePlatform;

class BaseManager extends ItemCallback
{
    public function __construct()
    {
        $this->Manager($this)->Query(function ($item, $manager) {
            return $manager->getModel() ? app($manager->getModel())?->newQuery() : null;
        })->PageName(function ($item, $manager) {
            $arr = explode('\\', $manager->getModel());
            if (count($arr) > 0) {
                return (str($arr[count($arr) - 1])->lower() . '-page' ?? 'page');
            } else {
                return  'page';
            }
        })->ModelForm(function (BaseManager $item, BaseManager $manager) {
            if ($manager->IsTable()) {
                return "formTable.";
            } else {
                return "form.";
            }
        });
    }
    public function CheckHook()
    {
        $this->ClearCache();
        do_action('BYTE_MANAGER_HOOK', $this);
        do_action('BYTE_MANAGER_HOOK_' . str($this->getKeyItem())->upper(), $this);
        return $this;
    }
    private  $___BeforeQuery = null;
    private $__model;
    public function BeforeQuery($callback)
    {
        $this->___BeforeQuery = $callback;
        return $this;
    }
    public function getBeforeQuery($model)
    {
        if ($this->___BeforeQuery && is_callable($this->___BeforeQuery)) {
            return ($this->___BeforeQuery)($model, $this);
        }
        return $model;
    }
    public function IsTable()
    {
        return false;
    }
    public function EditInTable($editInTable = null)
    {
        if ($editInTable == null) {
            $editInTable = function () {
                return true;
            };
        }
        return $this->setKeyValue('editInTable', $editInTable);
    }
    public function getEditInTable()
    {
        return $this->getValue('editInTable');
    }
    public function getMessage()
    {
    }
    public function Model($model)
    {
        $this->__model = $model;
        return $this;
    }
    public function ModelForm($ModelForm)
    {
        return $this->setKeyValue('ModelForm', $ModelForm);
    }
    public function getModelForm()
    {
        return $this->getValue('ModelForm');
    }
    public function getModel()
    {
        return $this->__model;
    }
    public function PageName($PageName)
    {
        return $this->setKeyValue('PageName', $PageName);
    }
    public function getPageName()
    {
        return $this->getValue('PageName');
    }
    public function PageSize($PageSize)
    {
        return $this->setKeyValue('PageSize', $PageSize);
    }
    public function getPageSize($size = 15)
    {
        return $this->getValue('PageSize', $size);
    }
    public function pageSizeList($pageSizeList)
    {
        if ($pageSizeList == null) {
            $pageSizeList = function () {
                return [5, 10, 15, 30, 50, 100, 150, 200, 300, 500, 10000];
            };
        }
        return $this->setKeyValue('pageSizeList', $pageSizeList);
    }
    public function getPageSizeList($pageSizeList = [5, 10, 15, 30, 50, 100, 150, 200, 300, 500, 10000])
    {
        return $this->getValue('pageSizeList', $pageSizeList);
    }

    public function Query($Query)
    {
        return $this->setKeyValue('Query', $Query);
    }
    public function getQuery()
    {
        return $this->getValue('Query');
    }


    private  $__actions = [];
    public function Action($key, $action)
    {
        $this->__actions[$key] = $action;
        return $this;
    }
    public function callDoAction($key, $params, $component)
    {
        if (isset($this->__actions[$key])) {
            if (is_callable($this->__actions[$key])) {
                return ($this->__actions[$key])($params, $component, $this);
            }
            if (is_string($this->__actions[$key]) && $action = app($this->__actions[$key])) {
                return $action($params, $component, $this);
            }
        }
        return [
            'error' => 'not found'
        ];
    }
    public function getItems()
    {
        return [];
    }
    public function LayoutDefault($LayoutDefault)
    {
        return $this->setKeyValue('LayoutDefault', $LayoutDefault);
    }
    public function getLayoutDefault()
    {
        return $this->getValue('LayoutDefault');
    }
    private  $___BeforeSave = null;
    public function BeforeSave($callback)
    {
        $this->___BeforeSave = $callback;
        return $this;
    }
    public function getBeforeSave($model)
    {
        if ($this->___BeforeSave && is_callable($this->___BeforeSave)) {
            return ($this->___BeforeSave)($model, $this);
        }
        return $model;
    }
    private  $___AfterSave = null;
    public function AfterSave($callback)
    {
        $this->___AfterSave = $callback;
        return $this;
    }
    public function getAfterSave($model)
    {
        if ($this->___AfterSave && is_callable($this->___AfterSave)) {
            return ($this->___AfterSave)($model, $this);
        }
        return $model;
    }
}
