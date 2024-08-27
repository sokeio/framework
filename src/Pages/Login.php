<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithThemeAdminGuest;

class Login extends \Sokeio\Page
{
    use WithThemeAdminGuest;

    protected static function pageName()
    {
        return 'login';
    }
    public function render()
    {
        return <<<html
        <div >
        Hello, Dashboard
        </div>
html;
    }
}
