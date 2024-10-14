<?php

namespace SokeioTheme\Admin\Pages\Auth;

use Sokeio\Theme;

class Register extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('pages.auth.register');
    }
}
