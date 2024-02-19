<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSetting;
use Sokeio\Facades\Theme;

class ThemeOptionSetting extends FormSetting
{
    protected function RedirectSave()
    {
        return false;
    }
    public function boot()
    {
        // Theme::SetupOption();
        parent::boot();
    }
    public function mount()
    {
        if (!theme_option()->checkOptionUI()) {
            return abort(404);
        }
        parent::mount();
    }

    protected function LoadSetting($keyForm, $keyValue, $column)
    {
        data_set($this, $keyForm, theme_option()->getValue($keyValue));
        return $this;
    }
    protected function SaveSetting($keyForm, $keyValue, $column)
    {
        theme_option()->setValue($keyValue, data_get($this, $keyForm), false);
        return $this;
    }
    protected function  saveDataAfter()
    {
        theme_option()->saveOption();
    }
    protected function getTitle()
    {
        return __('Theme Options');
    }
    public function SettingUI()
    {
        return theme_option()->getOptionUI();
    }
}
