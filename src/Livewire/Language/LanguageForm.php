<?php

namespace Sokeio\Livewire\Language;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Models\Language;

class LanguageForm extends Form
{
    public function getTitle()
    {
        return __('Language');
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    public function getButtons()
    {
        return [];
    }
    public function getModel()
    {
        return Language::class;
    }
    public function formUI()
    {
        return UI::container([
            UI::prex(
                'data',
                [
                    UI::row([
                        UI::column12([
                            UI::text('name')->label(__('Name'))->required()
                        ]),
                        UI::column12([
                            UI::text('code')->label(__('Code'))
                        ]),
                        UI::column12([
                            UI::text('flag')->label(__('Flag'))
                        ]),
                        UI::column12([
                            UI::checkBox('status')->label(__('Status'))->title(__('Active'))
                        ]),
                    ]),
                ]
            ),
        ])

            ->className('p-3');
    }
}
