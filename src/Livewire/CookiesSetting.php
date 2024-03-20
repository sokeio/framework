<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSetting;
use Sokeio\Components\UI;

class CookiesSetting extends FormSetting
{
    protected function redirectSave()
    {
        return false;
    }
    protected function getTitle()
    {
        return __('Cookies Settings');
    }
    private const MESSAGE_DEFAULT = 'We use cookies to give you the best experience on our website. ' .
        'By using our website you agree to our use of cookies.';
    public function SettingUI()
    {
        return UI::row([
            UI::column6([
                UI::checkBox('COOKIE_BANNER_ENABLE')->label(__('Banner Cookies Enable'))->valueDefault(1),
                UI::color('COOKIE_BANNER_COLOR')->label(__('Banner Cookies Color')),
            ]),
            UI::column6([
                UI::text('COOKIE_BUTTON_TEXT')
                    ->label(__('Button Text'))
                    ->valueDefault(__('Allow All Cookies'))
                    ->required(),
                UI::color('COOKIE_BUTTON_COLOR')->label(__('Button Color')),
            ]),
            UI::column([
                UI::textarea('COOKIE_BANNER_TEXT')
                    ->label(__('Banner Cookies Text'))
                    ->valueDefault(self::MESSAGE_DEFAULT)
                    ->required(),
            ])
        ]);
    }
}
