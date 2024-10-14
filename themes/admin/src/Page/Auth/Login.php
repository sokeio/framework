<?php

namespace SokeioTheme\Admin\Page\Auth;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: false, url: '/login', route: 'login', title: 'Login')]
class Login extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('pages.auth.login');
    }
}
