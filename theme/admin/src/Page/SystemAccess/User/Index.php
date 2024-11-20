<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Illuminate\Support\Facades\Log;
use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Column;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'User',
    menu: true,
    menuTitle: 'Users',
    menuTargetTitle: 'Users',
    menuTargetIcon: 'ti ti-users fs-2',
    menuTargetSort: 1000,
    sort: 0,
    model: User::class
)]
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
                        ->column('email')
                        ->column('phone_number')
                        ->query($this->getQuery())
                        ->enableIndex()
                        ->enableCheckBox()
                        ->searchbox(['name', 'email', 'phone_number'])
                        ->formSearch(
                            [],
                            [
                                Select::init('role_id')->label(__('Role'))->remoteActionWithModel(Role::class),
                            ]
                        )
                        ->columnAction([
                            Button::init()->text(__('Edit'))->className('btn ms-1 btn-primary btn-sm ')
                                ->modal(function (Button $button) {
                                    return route($this->getRouteName('edit'), [
                                        'dataId' =>
                                        $button->getParams('row')->id
                                    ]);
                                }),
                            Button::init()->text(__('Delete'))
                                ->wireClick(function ($params) {
                                    ($this->getModel())::find($params)->delete();
                                }, 'table_delete', function (Button $button) {
                                    return $button->getParams('row')->id;
                                })->className('btn btn-danger ms-1 btn-sm')
                                ->confirm(__('Are you sure?'))
                                ->when(function (Button $button) {
                                    return $button->getParams('row')->id > 1;
                                }),
                        ])
                ]
            )->rightUI([
                Button::init()
                    ->text(__('Add ' . $this->getPageConfig()->getTitle()))
                    ->icon('ti ti-plus')
                    ->modalRoute(
                        $this->getRouteName('edit'),
                        __('Add ' . $this->getPageConfig()->getTitle()),
                        'lg',
                        'ti ti-plus'
                    )
            ])
        ];
    }
}
