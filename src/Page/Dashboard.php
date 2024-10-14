<?php

namespace Sokeio\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(route: 'admin.dashboard', url: '/', admin: true, title: 'Dashboard', icon: 'fas fa-home')]
class Dashboard extends \Sokeio\Page
{
    public $test = "abc";
    public $users = [];

    public function change()
    {
        $this->test = 'changed';
    }
    public function render()
    {

        // Theme::js('window.sokeio.Application.run({
        // render(){
        //     return `<div>1[demo::test/]2<span>test</span> 3[demo::test/]4 </div>`;
        // }

        // },"#test-app");');
        Theme::templateFromPath(__DIR__ . '/test.js', 'demo-test');
        return <<<blade
        <div>
        @json(\$users)
            <button class="btn btn-primary" wire:modal wire:modal.title="Choà mọig nừo" wire:modal.url="{{route('sokeio-page.demo1')}}">demo</button>
            
        </div>
blade;
    }
}
