<?php

namespace ByteTheme\Admin\Livewire\Pages\Setting;

use BytePlatform\Component;
use BytePlatform\Facades\SettingForm;

class Index extends Component
{
    public $tabActive = 'overview';
    public $tabActiveIndex = 0;
    public function ChangeTab($tab)
    {
        $this->tabActive = $tab;
        $this->tabActiveIndex++;
    }
    public function render()
    {
        page_title('Setting');
        return view('theme::pages.setting.index', [
            'formWithTitle' => SettingForm::getFormWithTitles()
        ]);
    }
}
