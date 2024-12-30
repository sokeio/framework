<?php

namespace Sokeio\Page\Appearance;

use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Media Storage',
    icon: 'ti ti-cloud-data-connection',
    menu: true,
    menuTitle: 'Media Storage',
    sort: 999999999,
)]
class MediaStorage extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::make([
                Div::make()->attr('wire:media')->className('border border-gray-200 so-page-media')
            ])->rightUI([])

        ];
    }
}
