<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSetting;
use Sokeio\Components\UI;

class CookiesSetting extends FormSetting
{
    protected function RedirectSave()
    {
        return false;
    }
    protected function getTitle()
    {
        return __('Cookies Settings');
    }
    public function SettingUI()
    {
        return UI::Row([
            UI::Column6([
                UI::Checkbox('COOKIE_BANNER_ENABLE')->Label(__('Banner Cookies Enable'))->ValueDefault(1),
                UI::Color('COOKIE_BANNER_COLOR')->Label(__('Banner Cookies Color')),
            ]),
            UI::Column6([
                UI::Text('COOKIE_BUTTON_TEXT')->Label(__('Button Text'))->ValueDefault(__('Allow All Cookies'))->required(),
                UI::Color('COOKIE_BUTTON_COLOR')->Label(__('Button Color')),
            ]),
            UI::Column([
                UI::Textarea('COOKIE_BANNER_TEXT')->Label(__('Banner Cookies Text'))->ValueDefault('We use cookies to give you the best experience on our website. By using our website you agree to our use of cookies.')->required(),
            ])
        ]);
    }
}
