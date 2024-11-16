<?php

namespace Sokeio\Page\Appearance;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Media Library',
    menu: true,
    menuTitle: 'Media Library',
    sort: 999999999,
)]
class MediaLibrary extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->attr('wire:media')->className('border border-gray-200 so-page-media')
            ])->rightUI([])
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}