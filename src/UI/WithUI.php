<?php

namespace Sokeio\UI;

use Sokeio\FormData;

trait WithUI
{
    private SoUI|null $ui = null;
    public FormData $uiData;
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
    public function actionUI($name, $params = [])
    {
        return $this->getUI()->callAction($name, $params);
    }
}
