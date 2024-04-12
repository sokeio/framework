<?php

namespace Sokeio\Livewire\User;

use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Models\User;

class UserTable extends Table
{
    protected function getModel()
    {
        return User::class;
    }
    public function getTitle()
    {
        return __('User');
    }
    protected function getRoute()
    {
        return 'admin.system.user';
    }

    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('email')->label(__('Email')),
            UI::text('slug')->label(__('Slug'))
        ];
    }
}
