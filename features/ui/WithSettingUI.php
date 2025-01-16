<?php

namespace Sokeio\UI;


trait WithSettingUI
{
    use WithUI;
    public const COLUMN_GROUP = 'col-sm-12 col-md-12 col-lg-12';
    public const COLUMN_GROUP2 = 'col-sm-12 col-md-6 col-lg-4';
    public $formData = [];

    public function saveData()
    {
        $this->getUI()->saveInSetting();
        $this->alert('Setting has been saved!', 'Setting', 'success');
        $this->refreshPage();
    }

    public function reUI()
    {
        $this->ui = null;
        $this->getUI()->setup()->register();
        $this->getUI()->boot();
        if (!$this->isLivewire()) {
            $this->getUI()->loadInSetting();
        }
    }
}
