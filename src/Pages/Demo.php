<?php

namespace Sokeio\Pages;

use Sokeio\Page;

class Demo extends Page
{
    public function render()
    {
        return <<<HTML
            <div>
                <h1>Demo</h1>
            </div>
        HTML;
    }
}
