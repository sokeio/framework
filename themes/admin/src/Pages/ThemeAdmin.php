<?php

namespace SokeioTheme\Admin\Pages;

use Sokeio\Concerns\WithThemeAdminGuest;
use Sokeio\Theme;

class ThemeAdmin extends \Sokeio\Page
{
    use WithThemeAdminGuest;
    protected function pageTitle()
    {
        return 'Login';
    }
    protected static function pageName()
    {
        return 'login';
    }
    public function render()
    {
        return Theme::view('pages.test');
    }
}
