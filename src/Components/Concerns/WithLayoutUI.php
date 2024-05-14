<?php

namespace Sokeio\Components\Concerns;


trait WithLayoutUI
{
    use WithBreadcrumb;
    use WithActionUI;

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
                    $item->manager($this);
                    $item->boot();
                }
            }
        }
        return $layout;
    }
    public function boot()
    {
        if (!isLivewireRequestUpdated()) {
            if (method_exists($this, 'bootInitLayout')) {
                call_user_func([$this, 'bootInitLayout']);
            }
            $this->initLayout();
        }
    }
    public function updated()
    {
        $this->initLayout();
    }


    abstract protected function initLayout();
}
