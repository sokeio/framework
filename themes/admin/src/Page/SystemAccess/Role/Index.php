<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Roles', menu: true, menuTitle: 'Roles',sort:1)]
class Index extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init([Table::init()->column('name')->column('email')->column('phone_number')->query(User::query())])
                ->title(__('Roles'))
                ->className('container')
        ];
    }
}
