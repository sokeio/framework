<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

trait WithUI
{
    private SoUI|null $ui = null;
    public $dataSelecteds = [];
    public $dataChanges = [];
    public function isLivewire()
    {
        return Livewire::isLivewireRequest();
    }
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
        Log::info('booted');
        parent::booted();
        $this->reUI();
    }
    public function updated()
    {
        if (method_exists(parent::class, 'updated')) {
            parent::updated();
        }
        Log::info('updated');
        $this->reUI();
    }
    public function reUI()
    {
        $this->ui = null;
        $this->getUI()->setup()->register();
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
        Log::info('callActionUI');
        return $this->getUI()->callActionUI($name, $params);
    }
}
