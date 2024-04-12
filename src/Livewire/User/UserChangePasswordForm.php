<?php

namespace Sokeio\Livewire\User;

use Illuminate\Support\Facades\Password;
use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Models\User;

class UserChangePasswordForm extends Form
{
    public function getTitle()
    {
        return __('User');
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    public function getButtons()
    {
        return [];
    }
    public function getModel()
    {
        return User::class;
    }
    public $password_old;
    public $password_new;
    protected function doValidate()
    {
        parent::doValidate();
    }
    public function formUI()
    {
        return
            UI::Div(
                UI::row([
                    UI::column12([
                        UI::Password('password_old')->label(__('Password Old'))->required(),
                        UI::Password('password_new')->label(__('Password New'))->required(),
                    ]),

                ])
            )->className('p-3');
    }
}
