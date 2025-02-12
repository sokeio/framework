<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Column;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[AdminPageInfo(
    title: 'User',
    menu: true,
    menuTitle: 'Users',
    menuTargetTitle: 'Users',
    menuTargetIcon: 'ti ti-users',
    icon: 'ti ti-users',
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
            PageUI::make(
                [
                    Table::make()
                        ->column('name')
                        ->column('email')
                        ->column('phone_number')
                        ->column(Column::make('roles', 'roles')
                            ->classNameHeader('w-1')
                            ->editUI(Select::make('role_id')->remoteActionWithModel(Role::class))
                            ->cellUI(function (Div $column) {
                                return $column->getParams('row')
                                    ->roles()
                                    ->pluck('name')
                                    ->reduce(function ($carry, $item) {
                                        return "<span class=\"badge badge-info me-1\">" . $item . "</span>" . ' ' . $carry;
                                    }, '');
                            }))
                        ->query($this->getQuery()->with('roles'))
                        ->enableIndex()
                        ->enableCheckBox()
                        ->searchbox(['name', 'email', 'phone_number'])
                        ->formSearch(
                            [],
                            [
                                Select::make('role_id')->label(__('Role'))->remoteActionWithModel(Role::class)
                                    ->withQuery(
                                        function ($query, $value) {
                                            if (!$value) {
                                                return;
                                            }
                                            $query->whereHas('roles', function ($query) use ($value) {
                                                $query->where('id', $value);
                                            });
                                        }
                                    ),
                            ]
                        )
                        ->columnAction([
                            Button::make()->label(__('Edit'))->className('btn ms-1 btn-primary btn-sm ')
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
