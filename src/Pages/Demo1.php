<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdmin;

class Demo1 extends \Sokeio\Page
{
    use WithPageAdmin;
    
    public function render()
    {
        return <<<html
        <div >
        Hello, Dashboard
        </div>
html;
    }
}
