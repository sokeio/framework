<?php

namespace Sokeio\UI;


trait WithUI
{
    private SoUI|null $ui = null;
    public $dataSelecteds = [];
    public $dataChanges = [];
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
        $this->reUI();
    }
    public function updated()
    {
        if (method_exists(parent::class, 'updated')) {
            parent::updated();
        }
        $this->reUI();
    }
    public function reUI()
    {
        $this->ui = null;
        $this->getUI()->register();
        $this->getUI()->boot();
    }
    public function getUI(): SoUI
    {
        return $this->ui ?? ($this->ui = SoUI::make($this->setupUI(), $this));
    }
    protected function setupUI()
    {
        return [];
    }
    public function getPageTitle()
    {
        return $this->getPageConfig()->getTitle();
    }
    public function getPageIcon()
    {
        return $this->getPageConfig()->getIcon();
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
