<?php

namespace Sokeio\Page;

use Sokeio\Concerns\ThemeNone;
use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Page;
use Sokeio\UI\Common\Button;
use Sokeio\UI\WithUI;

class Demo extends Page
{
    use WithUI, ThemeNone;
    use WithPageAdminGuest;
    public function setupUI()
    {
        return [
            Button::init()->text('Click Me')->wireClick('alert("Nội dung alert")'),
        ];
    }
}
