<?php

namespace BytePlatform;

class BaseManager extends ItemCallback
{
    public function __construct()
    {
        $this->Query(function ($item, $manager) {
            return $manager->getModel() ? app($manager->getModel())?->newQuery() : null;
        })->PageName(function ($item, $manager) {
            $arr = explode('\\', $manager->getModel());
            if (count($arr) > 0) {
                return (str($arr[count($arr) - 1])->lower() . '-page' ?? 'page');
            } else {
                return  'page';
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
}
