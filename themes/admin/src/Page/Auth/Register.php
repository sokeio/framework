<?php

namespace SokeioTheme\Admin\Page\Auth;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: false, url: '/register', route: 'register',layout:'none', title: 'Register')]
class Register extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('pages.auth.register');
    }
}
