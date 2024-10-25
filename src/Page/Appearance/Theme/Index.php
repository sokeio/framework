<?php

namespace Sokeio\Page\Appearance\Theme;

use Livewire\Attributes\Url;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Theme Management',
    menu: true,
    menuTitle: 'Theme',
    sort: 0
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    #[Url(
        except: ''
    )]
    public $search = '';
    protected function setupUI()
    {
        return [
            PageUI::init([])->rightUI([
                Input::init()->fieldName('search')->debounce(500)->placeholder(__('Search'))->className('form-control'),
                Button::init()->text(__('Upload'))->icon('ti ti-upload')
                    ->className('btn btn-primary')
                    ->modalRoute($this->getRouteName('upload')),
                Button::init()->text(__('Marketplace'))->icon('ti ti-apps')->className('btn btn-success'),
            ])
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
