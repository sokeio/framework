<?php

namespace Sokeio\UI;


trait WithUI
{
    private SoUI|null $ui = null;
    public function queryStringWithUI()
    {
        return [
            'dataId' => ['except' => null]
        ];
    }
    public $dataId;
    public function boot()
    {
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
    public function render()
    {
        return view('sokeio::ui', ['ui' => $this->getUI()]);
    }
    public function callActionUI($name, $params = [])
    {
        return $this->getUI()->callActionUI($name, $params);
    }
}
