<?php

namespace Sokeio\Page\Setting;

use Illuminate\Support\Facades\Artisan;
use Sokeio\Attribute\PageInfo;
use Sokeio\Platform;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'License',
    menu: true,
    menuTitle: 'License',
    icon: 'ti ti-license',
    sort: 999999999,
)]
class License extends \Sokeio\Page
{
    use WithUI;
    public $license_key;
    public function mount()
    {
        $this->license_key = config('license.key');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Card::make([
                    Div::make([
                        Input::make('license_key')
                            ->label(__('License Key'))
                            ->placeholder('License Key'),
                        Div::make([
                            Button::make()->text(__('Activate'))
                            ->className('btn btn-success mt-3')
                            ->icon('ti ti-check')
                            ->wireClick(function (Button $button) {
                                $this->alert($button->getWireValue('license_key'), 'License Key', 'success');
                                Artisan::call('license:activate', [
                                    'license' => $button->getWireValue('license_key')
                                ]);
                            })
                        ])->className('text-center'),
                    ])->container()
                    ->className('')
                ])->hideSwitcher()
                    ->title(__('License Information'))
            ])->rightUI([])
                ->hidePageHeader()

        ];
    }
}
