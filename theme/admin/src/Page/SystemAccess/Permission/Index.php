<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Permission;

use Sokeio\Models\Permission;
use Sokeio\Models\User;
use Sokeio\Platform;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Permission',
    menu: true,
    menuTitle: 'Permissions',
    model: Permission::class
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    public function updatePermissions()
    {
        Platform::gate()->updatePermission();
    }
    protected function setupUI()
    {
        return [
            PageUI::init(
                [
                    Table::init()
                        ->column('name')
                        ->column('slug')
                        ->column('group')
                        ->query($this->getQuery())
                        ->enableIndex()
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
                    ->text(__('Upadate Permissions'))
                    ->wireClick('updatePermissions')
                    ->className('btn btn-success')
                    ->icon('ti ti-refresh'),
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
