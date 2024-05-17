<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Breadcrumb;

trait WithViewUI
{
    use WithLayoutUI;

    protected function viewUI()
    {
        return [];
    }
    protected $layout;
    protected function initLayout()
    {
        if (!$this->layout) {
            $this->layout = $this->reLayout($this->viewUI());
        }
    }
    public function render()
    {
        return view('sokeio::components.layout', [
            'layout' => $this->layout
        ]);
    }
}
