<?php

namespace Sokeio\Page;

use Sokeio\Concerns\WithPageAdmin;
use Sokeio\Concerns\WithPageAdminGuest;

class Demo1 extends \Sokeio\Page
{
    use WithPageAdminGuest;
    public $test = 'test';
    public function change()
    {
        $this->test = 'changed_' . time();
    }
    public function render()
    {
        return <<<html
        <div style="height:500px" data-modal-size="lg" data-skip-overlay="true" data-hide-close="true">
        {{\$test}}
        Hello, Dashboard <button wire:click="change">Test</button>
        @if(\$test!='test')
        <button so-on:click="this.delete()">Close</button>
        @endif
        </div>
html;
    }
}