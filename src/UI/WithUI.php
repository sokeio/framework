<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Log;

trait WithUI
{
    private SoUI|null $ui = null;
    public $dataSelecteds = [];
    public function getModel()
    {
        return $this->getPageConfig()->getModel();
    }
    public function getQuery()
    {
        if ($model = $this->getModel()) {
            return ($model)::query();
        }
        return null;
    }
    public function booted()
    {
        parent::booted();
        $this->getUI()->boot();
    }
    public function reUI()
    {
        $this->ui = null;
        $this->getUI()->boot();
    }
    public function getUI(): SoUI
    {
        return $this->ui ?? ($this->ui = SoUI::init($this->setupUI(), $this));
    }
    protected function setupUI()
    {
        return [];
    }
    public function getPageTitle()
    {
        return $this->getPageConfig()->getTitle();
    }
    public function render()
    {
        return view('sokeio::ui', ['ui' => $this->getUI()]);
    }
    public function callActionUI($name, $params = [])
    {
        return $this->getUI()->callActionUI($name, $params);
    }
}
