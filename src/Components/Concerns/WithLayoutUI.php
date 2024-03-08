<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Breadcrumb;

trait WithLayoutUI
{
    protected function getTitle()
    {
        return null;
    }
    protected function getBreadcrumb()
    {
        if (sokeioIsAdmin()) {
            return [
                Breadcrumb::Item(__('Home'), route('admin.dashboard'))
            ];
        }
        return [
            Breadcrumb::Item(__('Home'), url(''))
        ];
    }
    protected function doBreadcrumb()
    {
        breadcrumb()->Title($this->getTitle())->Breadcrumb($this->getBreadcrumb());
    }
    private $actionUI = [];
    public function addActionUI($actonKey, $actonFn)
    {
        if (!isset($this->actionUI[$actonKey])) {
            $this->actionUI[$actonKey] = $actonFn;
        }
        return $this;
    }
    public function callActionUI($actonKey, ...$arg)
    {
        if (isset($this->actionUI[$actonKey])) {
            return call_user_func($this->actionUI[$actonKey], $this, ...$arg);
        }
    }
    private $inputUI = [];
    public function addInputUI($inputUI, $key = 'data')
    {
        if (isset($this->inputUI[$key])) {
            $this->inputUI[$key][] = $inputUI;
        } else {
            $this->inputUI[$key] = [$inputUI];
        }
        return $this;
    }
    /**
     * Get a new query builder for the model's table.
     *
     *
     * @return \Sokeio\Components\Field\BaseField[]
     */
    protected function getInputUI($key = 'data')
    {
        return $this->inputUI[$key] ?? [];
    }
    protected function getAllInputUI()
    {
        $arr = [];
        foreach ($this->inputUI as $value) {
            $arr = array_merge($arr, $value);
        }
        return $arr;
    }
    protected function reLayout($layout)
    {
        if ($layout) {
            if (is_object($layout)) {
                $layout = [$layout];
            }
            foreach ($layout as $item) {
                if ($item) {
                    $item->Manager($this);
                    $item->boot();
                }
            }
        }
        return $layout;
    }
    public $testLayout;
    public function boot()
    {
        if (!isLivewireRequestUpdated()) {
            $this->initLayout();
        }
    }
    public function updated()
    {
        $this->initLayout();
    }


    abstract protected function initLayout();
}
