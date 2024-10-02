<?php

namespace Sokeio\Pages;

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
        <div style="height:500px">
        {{\$test}}
        Hello, Dashboard <button wire:click="change">Test</button>
        </div>
html;
    }
}
