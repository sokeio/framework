<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;
class Dashboard extends \Sokeio\Page
{
    use WithPageAdminGuest;
    public $test;
    public static function pageUrl()
    {
        return '/';
    }
    public function change()
    {
        $this->test = 'changed';
    }
    public function render()
    {
        return <<<html
        <div>
        </div>
html;
    }
}
