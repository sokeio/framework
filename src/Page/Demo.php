<?php

namespace Sokeio\Page;

use App\Models\User;
use Sokeio\Concerns\ThemeNone;
use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Page;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Div;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

class Demo extends Page
{
    use WithUI, ThemeNone;
    use WithPageAdminGuest;
    public function setupUI()
    {
        return Div::init()
            ->className('container p-2')
            ->warp([
                Button::init()->text('Click Me')->click('alert("Nội dung alert")')->className('p-2 mb-2'),
                Table::init()
                    ->column('username')
                    ->column('email')
                    ->className('table table-bordered')
                    ->query(User::query()),
            ]);
    }
}
