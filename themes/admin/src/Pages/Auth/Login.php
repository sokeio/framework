<?php

namespace SokeioTheme\Admin\Pages\Auth;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: false, url: '/login')]
class Login extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('pages.auth.login');
    }
}
