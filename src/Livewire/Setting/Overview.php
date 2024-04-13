<?php

namespace Sokeio\Livewire\Setting;

use Sokeio\Components\FormSetting;
use Sokeio\Components\UI;

class Overview extends FormSetting
{
    public function getTitle()
    {
        return __('System Setting');
    }
    protected function saveDataAfter()
    {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
    }

    protected function SettingUI()
    {
        return UI::row(applyFilters('SOKEIO_ADMIN_SETTING_OVERVIEW', [
            UI::row([
                UI::column4([
                    UI::select('PLATFORM_ADMIN_LAYOUT_DEFAULT')
                        ->label(__('Layout Admin'))
                        ->dataSource(function () {
                            return [
                                [
                                    'id' => 'default',
                                    'name' => 'Default'
                                ],
                                [
                                    'id' => 'default-navbar',
                                    'name' => 'Navbar'
                                ]
                            ];
                        }),
                ]),
                UI::column5([
                    UI::text('PLATFORM_SYSTEM_NAME')->label(__('System Admin Name')),

                ]),
                UI::column3([
                    UI::image('PLATFORM_SYSTEM_LOGO')->label(__('System Admin Logo')),
                ]),
            ]),

            // UI::column6([
            //     UI::select('PLATFORM_SYSTEM_LOCALE_DEFAULT')
            //         ->label(__('System Language'))
            //         ->fieldKey('code')
            //         ->dataSource(function () {
            //             return Language::query()->where('status', 1)->get();
            //         })
            // ]),
            UI::column6([
                UI::text('GOOGLE_GA_ID')->label(__('Google Analytics ID')),
            ]),
        ]));
    }
}
