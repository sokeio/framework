<?php

namespace SokeioTheme\Admin\Pages;

use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Theme;

class ThemeAdmin extends \Sokeio\Page
{
    use WithPageAdminGuest;
    protected function pageTitle()
    {
        return 'Login';
    }
    public static function pageName()
    {
        return 'login';
    }
    public function render()
    {
        return Theme::view('pages.test');
    }
}
