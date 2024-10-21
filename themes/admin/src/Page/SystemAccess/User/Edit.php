<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'User Form')]
class Edit extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            ModalUI::init()->title($this->dataId ? __('Edit User') : __('Create User'))
        ];
    }
}
