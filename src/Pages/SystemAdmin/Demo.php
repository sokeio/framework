<?php

namespace Sokeio\Pages\SystemAdmin;

use Livewire\Livewire;
use Sokeio\Concerns\WithPageAdmin;
use Sokeio\Platform;

class Demo extends \Sokeio\Page
{
    use WithPageAdmin;
    public $test = 'test';
    public function change()
    {
        $this->test = 'changed';
    }
    public function render()
    {
        $isAdmin =  Platform::isUrlAdmin() ? 'admin' : 'user';
        $url = Livewire::originalPath();
        return <<<html
        <div >
        Demo, {$isAdmin},{$url}
        <input type="text" wire:model="test">
        <button wire:click="change">Change</button>
        </div>
html;
    }
}
