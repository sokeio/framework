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
class CacheSystem extends \Sokeio\Page
{
    use WithUI;
    const COLUMN = 'col-lg-4 col-md-6 col-sm-12';
    protected function setupUI()
    {
        $setting = [
            [
                'title' => 'Clear Cache',
                'subtitle' => 'it will clear all cache in the system',
                'message-success' => 'Cache has been cleared!',
                'column' => self::COLUMN,
                'fn' => function () {
                    Artisan::call('cache:clear');
                },
                'action' => 'sokeio::system::cache.clear',
            ],
            [
                'title' => 'Clear View',
                'subtitle' => 'it will clear all View in the system',
                'message-success' => 'View has been cleared!',
                'column' => self::COLUMN,
                'fn' => function () {
                    Artisan::call('view:clear');
                },
                'action' => 'sokeio::system::view.clear',
            ],
            [
                'title' => 'Clear Config',
                'subtitle' => 'it will clear all Config in the system',
                'message-success' => 'Config has been cleared!',
                'column' => self::COLUMN,
                'fn' => function () {
                    Artisan::call('config:clear');
                },
                'action' => 'sokeio::system::config.clear',
            ]
        ];
        return [
            PageUI::init([
                Div::init(
                    collect($setting)->map(function ($item) {
                        return Card::init([
                            Div::init(
                                Button::init()->text($item['title'])
                                    ->className('btn btn-primary')
                                    ->wireClick(function () use ($item) {
                                        $item['fn']();
                                        $this->alert($item['message-success'], $item['title'], 'success');
                                    }, $item['action'])
                            )->className('p-2 text-center')
                        ])
                            ->title($item['title'])
                            ->subtitle($item['subtitle'])
                            ->className('mb-2')->column(
                                $item['column']
                            );
                    })->toArray()
                )->row()
            ])->rightUI([])
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
