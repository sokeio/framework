<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Theme;

class Dashboard extends \Sokeio\Page
{
    use WithPageAdminGuest;
    public $test = "abc";
    public $users = [];
    public static function pageUrl()
    {
        return '/';
    }
    public function change()
    {
        $this->test = 'changed';
    }
    public function mount()
    {
        $this->users =    Role::all();
    }
    public function render()
    {

        // Theme::js('window.sokeio.Application.run({
        // render(){
        //     return `<div>1[demo::test/]2<span>test</span> 3[demo::test/]4 </div>`;
        // }

        // },"#test-app");');
        Theme::templateFromPath(__DIR__ . '/test.js', 'demo-test');
        return <<<html
        <div>
        @json(\$users)
            <button class="btn btn-primary" wire:modal wire:modal.title="Choà mọig nừo" wire:modal.url="{{route('admin.sokeio-page.demo1')}}">demo</button>
            
        </div>
html;
    }
}
