<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSetting;
use Sokeio\Facades\Theme;

class ThemeOptionSetting extends FormSetting
{
    protected function redirectSave()
    {
        return false;
    }
  
    public function mount()
    {
        if (!themeOption()->checkOptionUI()) {
            return abort(404);
        }
        parent::mount();
    }

    protected function LoadSetting($keyForm, $keyValue, $column)
    {
        data_set($this, $keyForm, themeOption()->getValue($keyValue));
        return $this;
    }
    protected function SaveSetting($keyForm, $keyValue, $column)
    {
        themeOption()->setValue($keyValue, data_get($this, $keyForm), false);
        return $this;
    }
    protected function  saveDataAfter()
    {
        themeOption()->saveOption();
    }
    protected function getTitle()
    {
        return __('Theme Options');
    }
    public function SettingUI()
    {
        return themeOption()->getOptionUI();
    }
}
