<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;

class Dashboard extends \Sokeio\Page
{
    use WithPageAdminGuest;
    public static function pageUrl()
    {
        return '/';
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
