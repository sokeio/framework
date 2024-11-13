<?php

namespace Sokeio\Page\Setting;

use Illuminate\Support\Facades\Artisan;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Common\Div;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Cache System',
    menu: true,
    menuTitle: 'Cache System',
    sort: 999999999,
)]
class Cache extends \Sokeio\Page
{
    use WithUI;
    const COLUMN = 'col-lg-4 col-md-6 col-sm-12';
    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init([
                    Card::init([
                        Div::init(
                            Button::init()->text(__('Clear Cache System'))
                                ->className('btn btn-primary')
                                ->wireClick(function () {
                                    Artisan::call('cache:clear');
                                    $this->alert(__('Cache has been cleared!'));
                                }, 'sokeio::system::cache.clear')
                        )->className('p-2 text-center')

                    ])
                        ->title(__('Clear Cache System'))
                        ->subtitle(__('it will clear all cache in the system'))
                        ->className('mb-2')->column(self::COLUMN),
                    Card::init([
                        Div::init(
                            Button::init()->text(__('Clear View System'))
                                ->className('btn btn-primary')
                                ->wireClick(function () {
                                    Artisan::call('view:clear');
                                    $this->alert(__('View has been cleared!'));
                                }, 'sokeio::system::view.clear')
                        )->className('p-2 text-center')

                    ])
                        ->title(__('Clear View System'))
                        ->subtitle(__('it will clear all View in the system'))
                        ->className('mb-2')->column(self::COLUMN),
                    Card::init([
                        Div::init(
                            Button::init()->text(__('Clear Config System'))
                                ->className('btn btn-primary')
                                ->wireClick(function () {
                                    Artisan::call('config:clear');
                                    $this->alert(__('Config has been cleared!'));
                                }, 'sokeio::system::config.clear')
                        )->className('p-2 text-center')

                    ])
                        ->title(__('Clear Config System'))
                        ->subtitle(__('it will clear all Config in the system'))
                        ->className('mb-2')->column(self::COLUMN),
                ])->row()
            ])->rightUI([])
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
