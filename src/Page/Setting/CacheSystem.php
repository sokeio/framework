<?php

namespace Sokeio\Page\Setting;

use Illuminate\Support\Facades\Artisan;
use Sokeio\Attribute\PageInfo;
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
    private function settingClear()
    {
        return [
            [
                'title' => 'Clear Cache',
                'icon' => 'ti ti-playlist-x',
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
                'icon' => 'ti ti-script-x',
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
                'icon' => 'ti ti-adjustments-minus',
                'subtitle' => 'it will clear all Config in the system',
                'message-success' => 'Config has been cleared!',
                'column' => self::COLUMN,
                'fn' => function () {
                    Artisan::call('config:clear');
                },
                'action' => 'sokeio::system::config.clear',
            ]
        ];
    }
    public function clearAll()
    {
        foreach ($this->settingClear() as $item) {
            try {
                $item['fn']();
            } catch (\Exception $e) {
                $this->alert($e->getMessage(), $item['title'], 'danger');
                return;
            }
        }
        $this->alert('All cache has been cleared!', 'Cache System', 'success');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Div::make(
                    collect($this->settingClear())->map(function ($item) {
                        return Card::make([
                            Div::make(
                                Button::make()->text($item['title'])
                                    ->className('btn btn-primary')
                                    ->icon(($item['icon'] ?? 'ti ti-trash') . ' fs-4 ')
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
            ])->rightUI([
                Button::make()->text(__('Clear All Cache'))
                    ->icon('ti ti-trash')
                    ->className('btn btn-danger')
                    ->wireClick('clearAll')
            ])

        ];
    }
}
