<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Log;

trait WithSettingUI
{
    use WithUI;
    public const COLUMN_GROUP = 'col-sm-12 col-md-12 col-lg-12';
    public const COLUMN_GROUP2 = 'col-sm-12 col-md-6 col-lg-6';
    public $formData = [];

    public function saveData()
    {
        $this->getUI()->saveInSetting();
        $this->alert('Setting has been saved!', 'Setting', 'success');
        $this->refreshPage();
    }
    public function mount()
    {
        $this->getUI()->tap(function ($ui) {
            $ui->boot();
            $ui->loadInSetting();
            $ui->skipBoot();
        });
    }
}
