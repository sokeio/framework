<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;

class Login extends \Sokeio\Page
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
        return <<<html
        <div >
        Hello, Dashboard
        </div>
html;
    }
}
