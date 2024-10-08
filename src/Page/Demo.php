<?php

namespace Sokeio\Page;

use App\Models\User;
use Sokeio\Concerns\ThemeNone;
use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Page;
use Sokeio\Theme;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

class Demo extends Page
{
    use WithUI;
    use WithPageAdminGuest;
    public $demoName = '';
    public function alertTest($demoName)
    {
        $this->alert($this->demoName);
        $this->demoName = $demoName;
    }
    public function setupUI()
    {
        return Div::init()
            ->className('container p-2')
            ->warp([
                Button::init()->text('Click Me')->wireClick('alertTest("Nội dung alert")')->className('p-2 mb-2'),
                Input::init()->fieldName('demoName')->date(),
                Table::init()
                    ->column('username')
                    ->column('email')
                    ->query(User::query()),
            ]);
    }
}
