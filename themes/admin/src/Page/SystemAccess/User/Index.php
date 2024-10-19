<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Illuminate\Support\Facades\Hash;
use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Users', menu: true, menuTitle: 'Users', menuTargetTitle: 'System Access', sort: 0)]
class Index extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init(
                [
                    Table::init()
                        ->context($this->soData)
                        ->column('name')
                        ->column('email')
                        ->column('phone_number')
                        ->query(User::query())
                        ->page()
                        ->enableIndex()
                ]
            )->rightUI([
                Button::init()
                    ->text(__('Create User'))
                    ->modalRoute($this->getRouteName('edit'), __('Create User'), 'lg', 'ti ti-plus')
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
