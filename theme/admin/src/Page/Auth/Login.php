<?php

namespace SokeioTheme\Admin\Page\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Sokeio\Attribute\PageInfo;
use Sokeio\Platform;
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
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->isRememberMe)) {
            $this->addError('account_error', __('Invalid account or password'));
            return;
        }
        if (!Platform::gate()->applyFilter(Auth::user(), true)) {
            Platform::gate()->setUser(Auth::user());
            if (!Platform::gate()->isSupperAdmin() || !setting('SOKEIO_LOGIN_SUPPORT_FILTER_USER_SUPPER_ADMIN', true)) {
                $this->addError('account_error', __('You are not allowed to login'));
                return;
            }
        }
        $this->refreshPage(urldecode($this->urlRef ?? '/'));
    }
    public function render()
    {
        return Theme::view('pages.auth.login');
    }
}
