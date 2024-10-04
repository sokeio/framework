<?php

namespace Sokeio\UI;

trait WithUI
{
    private SoUI|null $ui = null;
    public function boot()
    {
        $this->getUI()->boot();
    }
    public function getUI()
    {
        return $this->ui ?? ($this->ui = SoUI::init($this->setupUI(), $this));
    }
    public function setupUI()
    {
        return [];
    }
    public function render()
    {
        return view('sokeio::ui', ['ui' => $this->getUI()]);
    }
}
