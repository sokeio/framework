<?php

namespace SokeioTheme\Admin\Pages\Auth;

use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Theme;

class Register extends \Sokeio\Page
{
    use WithPageAdminGuest;
    protected function pageTitle()
    {
        return 'Register';
    }
    public static function pageName()
    {
        return 'register';
    }
    public function render()
    {
        return Theme::view('pages.auth.register');
    }
}
