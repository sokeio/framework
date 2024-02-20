<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Breadcrumb;

trait WithLayoutUI
{
    protected function getTitle()
    {
    }
    protected function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
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
        foreach ($this->inputUI as $key => $value) {
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
        if (!is_livewire_reuqest_updated())
            $this->initLayout();
        // parent::boot();
    }
    public function updated()
    {
        $this->initLayout();
    }


    protected function initLayout()
    {
    }
}
