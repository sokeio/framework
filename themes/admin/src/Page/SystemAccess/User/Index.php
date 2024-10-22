<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Column;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Users',
    menu: true,
    menuTitle: 'Users',
    menuTargetTitle: 'System Access',
    sort: 0
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
                        // ->tableKey('users')
                        ->column('name', function (Column $column) {
                            // $column->classNameCell(function ($row) {
                            // });
                        })
                        ->column('email')
                        ->column('phone_number', function (Column $column) {
                            $column->classNameCell(function ($row) {
                                return $row->phone_number ? '' : 'bg-warning';
                            });
                        })
                        ->query(User::query())
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
                                    User::find($params)->delete();
                                }, 'table_users_delete', function (Button $button) {
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
                    ->text(__('Add User'))
                    ->icon('ti ti-plus')
                    ->modalRoute($this->getRouteName('edit'), __('Add User'), 'lg', 'ti ti-plus')
            ])
                ->title(__('Users'))
                ->className('container')
        ];
    }
    public function createUser()
    {
        $this->alert($this->getRouteName('edit'));
    }
}
