<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Sokeio\Models\Role;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Roles', menu: true, menuTitle: 'Roles', sort: 1)]
class Index extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init(
                [
                    Table::init()
                        ->column('name')
                        ->column('slug')
                        ->query(Role::query())
                        ->enableIndex()
                        ->enableCheckBox()
                        ->columnAction([
                            Button::init()->text(__('Edit'))->className('btn btn-primary btn-sm ')
                                ->modal(function (Button $button) {
                                    return route($this->getRouteName('edit'), [
                                        'dataId' =>
                                        $button->getParams('row')->id
                                    ]);
                                }),
                            Button::init()->text(__('Delete'))
                                ->wireClick(function ($params) {
                                    $this->alert('test' . $params);
                                }, 'table_roles_delete', function (Button $button) {
                                    return $button->getParams('row')->id;
                                })->className('btn btn-danger ms-1 btn-sm'),

                        ])
                ]
            )
                ->title(__('Roles'))
                ->className('container')->rightUI([
                    Button::init()
                        ->text(__('Add Role'))
                        ->icon('ti ti-plus')
                        ->modalRoute($this->getRouteName('edit'), __('Add Role'), 'lg', 'ti ti-plus')
                ])
        ];
    }
}
