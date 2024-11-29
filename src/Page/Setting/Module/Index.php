<?php

namespace Sokeio\Page\Setting\Module;

use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Module System',
    menu: true,
    menuTitle: 'Module',
    icon: 'ti ti-apps ',
    sort: 15
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
            PageUI::init([
                Input::init()->fieldName('search')->debounce(500)->placeholder(__('Search'))->className('form-control'),
                Div::init()->viewBlade('sokeio::pages.module.index', [
                    'datas' => Platform::module()->getAll(),
                    'routeName' => $this->getRouteName('info'),
                ])->className('mt-3'),
            ])->rightUI([
                Button::init()->text(__('Create'))->icon('ti ti-table-plus')
                    ->className('btn btn-warning')
                    ->modalRoute($this->getRouteName('create'))->when(function () {
                        if (config('app.env') == 'local' && env('SOKEIO_MODE_DEV') == 'true') {
                            return true;
                        }
                        return false;
                    }),
                Button::init()->text(__('Upload'))->icon('ti ti-upload')
                    ->className('btn btn-primary')
                    ->modalRoute($this->getRouteName('upload')),
                Button::init()->text(__('Marketplace'))->icon('ti ti-apps')
                    ->className('btn btn-success')->modalRoute($this->getRouteName('marketplace')),
            ])

        ];
    }
}
