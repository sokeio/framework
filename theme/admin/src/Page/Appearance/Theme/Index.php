<?php

namespace SokeioTheme\Admin\Page\Appearance\Theme;

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
    title: 'Theme System',
    menu: true,
    menuTitle: 'Theme',
    menuTargetSort: 99991,
    menuTargetIcon: 'ti ti-palette',
    icon: 'ti ti-brush',
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
            PageUI::make([
                Input::make()->fieldName('search')->debounce(500)->placeholder(__('Search'))->className('form-control'),
                Div::make()->viewBlade('sokeio::pages.appearance.theme.index', [
                    'datas' => Platform::getThemeSite(),
                    'routeName' => $this->getRouteName('info'),
                ])->className('mt-3'),
            ])->rightUI([
                Button::make()->text(__('Create'))->icon('ti ti-table-plus')
                    ->className('btn btn-warning')
                    ->modalRoute($this->getRouteName('create'))
                    ->when(function () {
                        if (config('app.env') == 'local' && env('SOKEIO_MODE_DEV') == 'true') {
                            return true;
                        }
                        return false;
                    }),
                // Button::make()->text(__('Upload'))->icon('ti ti-upload')
                //     ->className('btn btn-primary')
                //     ->modalRoute($this->getRouteName('upload')),
                // Button::make()->text(__('Marketplace'))->icon('ti ti-apps')
                //     ->className('btn btn-success')->modalRoute($this->getRouteName('marketplace')),
            ])

        ];
    }
}
