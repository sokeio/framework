<?php

namespace SokeioTheme\Admin\Page\Setting\Module;

use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(
    title: 'Module System',
    menu: true,
    menuTitle: 'Module',
    icon: 'ti ti-apps ',
    sort: -50
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
            PageUI::make([
                Input::make()->fieldName('search')->debounce(500)->placeholder(__('Search'))->className('form-control'),
                Div::make()->viewBlade('sokeio::pages.module.index', [
                    'datas' => Platform::module()->getAll(),
                    'routeName' => $this->getRouteName('info'),
                ])->className('mt-3'),
            ])->rightUI([
                Button::make()->label(__('Create'))->icon('ti ti-table-plus')
                    ->className('btn btn-warning')
                    ->modalRoute($this->getRouteName('create'))
                    ->when(function () {
                        if (config('app.env') == 'local' && env('SOKEIO_MODE_DEV') == 'true') {
                            return true;
                        }
                        return false;
                    }),
                // Button::make()->label(__('Upload'))->icon('ti ti-upload')
                //     ->className('btn btn-primary')
                //     ->modalRoute($this->getRouteName('upload')),
                // Button::make()->label(__('Marketplace'))->icon('ti ti-apps')
                //     ->className('btn btn-success')->modalRoute($this->getRouteName('marketplace')),
            ])

        ];
    }
}
