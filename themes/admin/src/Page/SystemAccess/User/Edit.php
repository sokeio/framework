<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Field\Input;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'User Form')]
class Edit extends \Sokeio\Page
{
    use WithUI;
    public function mount()
    {
        $data =  $this->dataId ? User::find($this->dataId) : new User();
        $this->formData->fill($data);
    }
    protected function setupUI()
    {
        return [
            ModalUI::init([
                Input::init('name')->label(__('Name')),
                Input::init('email')->label(__('Email')),
                Input::init('phone_number')->label(__('Phone')),
            ])->title($this->dataId ? __('Edit User') : __('Create User'))
                ->className('p-2')->setPrefix('formData')
        ];
    }
}
