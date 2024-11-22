<?php

namespace SokeioTheme\Admin\Page\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Sokeio\Attribute\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: false, url: '/login', route: 'login', title: 'Login', layout: 'conver')]
class Login extends \Sokeio\Page
{
    #[Url('url_ref')]
    public $urlRef = null;
    #[Rule('required')]
    public $email;
    #[Rule('required')]
    public $password;
    public $isRememberMe = false;

    public function login()
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->isRememberMe)) {
            $this->refreshPage(urldecode($this->urlRef ?? '/'));
        } else {
            $this->addError('account_error', 'Invalid account or password');
        }
    }
    public function render()
    {
        return Theme::view('pages.auth.login');
    }
}
