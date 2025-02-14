<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Sokeio\Models\Role;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[AdminPageInfo(
    title: 'Role',
    icon: 'ti ti-user-check',
    menu: true,
    menuTitle: 'Roles',
    sort: 1,
    model: Role::class,
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::make(
                [
                    Table::make()
                        ->column('name')
                        ->column('slug')
                        ->column('description')
                        ->query($this->getQuery())
                        ->enableIndex()
                        ->columnAction([
                            Button::make()->label(__('Edit'))->className('btn btn-primary btn-sm ')
                                ->modal(function (Button $button) {
                                    return route($this->getRouteName('edit'), [
                                        'dataId' =>
                                        $button->getParams('row')->id
                                    ]);
                                }),
                            Button::make()->label(__('Delete'))
                                ->wireClick(function ($params) {
                                    ($this->getModel())::find($params)->delete();
                                }, 'table_delete', function (Button $button) {
                                    return $button->getParams('row')->id;
                                })->className('btn btn-danger ms-1 btn-sm')
                                ->confirm(__('Are you sure?'))
                                ->when(function (Button $button) {
                                    return $button->getParams('row')->id > 1;
                                }),
                        ])->rightUI([
                            Button::make()
                                ->label(__('Add ' . $this->getPageConfig()->getTitle()))
                                ->icon('ti ti-plus')
                                ->modalRoute(
                                    $this->getRouteName('edit'),
                                    __('Add ' . $this->getPageConfig()->getTitle()),
                                    'lg',
                                    'ti ti-plus'
                                )
                        ])
                ]
            )


        ];
    }
}
