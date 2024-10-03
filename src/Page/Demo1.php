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
        <div style="height:500px" data-modal-size="lg" data-skip-overlay="true">
        {{\$test}}
        Hello, Dashboard <button wire:click="change">Test</button>
        </div>
html;
    }
}
