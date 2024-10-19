<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

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
    public function createUser()
    {
        $this->alert('test');
    }
    protected function setupUI()
    {
        return [
            PageUI::init(
                [
                    Table::init()
                        ->column('name')
                        ->column('email')
                        ->column('phone_number')
                        ->query(User::query())
                ]
            )->rightUI([
                Button::init()->text(__('Create User'))->wireClick('createUser()')
            ])
                ->title(__('Users'))
                ->className('container')
        ];
    }
}
