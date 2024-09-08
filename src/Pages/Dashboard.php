<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Support\Menu\MenuManager;
use Sokeio\Platform;

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
        $isAdmin =  Platform::isUrlAdmin() ? 'admin' : 'user';
        $url = Platform::currentUrl();
        $arr=json_encode( MenuManager::admin()->toArray(), JSON_PRETTY_PRINT);
        return <<<html
        <div>
        Demo, {$isAdmin},{$url},
        <input type="text" wire:model="test">
        <button wire:click="change">Change</button>
        <pre>{$arr}</pre>
        </div>
html;
    }
}
