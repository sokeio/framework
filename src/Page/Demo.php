<?php

namespace Sokeio\Page;

use Sokeio\page;
use Sokeio\UI\Field\FieldUI;
use Sokeio\UI\WithUI;

class Demo extends page
{
    use WithUI;
    public function setupUI()
    {
        return [
            FieldUI::init()->className('error')->id('error'),
        ];
    }
}
