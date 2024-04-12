<?php

namespace Sokeio\Livewire\Setting;

use Sokeio\Components\FormSetting;
use Sokeio\Components\UI;
use Sokeio\Models\Language;

class Overview extends FormSetting
{
    public function getTitle()
    {
        return __('System Setting');
    }
    protected function SettingUI()
    {
        return UI::row(applyFilters('SOKEIO_ADMIN_SETTING_OVERVIEW', [
            UI::column6([
                UI::text('PLATFORM_SYSTEM_NAME')->label(__('System Admin Name')),
            ]),
            UI::column6([
                UI::select('PLATFORM_SYSTEM_LOCALE_DEFAULT')
                    ->label(__('System Language'))
                    ->fieldKey('code')
                    ->dataSource(function () {
                        return Language::query()->where('status', 1)->get();
                    })
            ]),
            UI::column6([
                UI::text('GOOGLE_GA_ID')->label(__('Google Analytics ID')),
            ]),
        ]));
    }
}
