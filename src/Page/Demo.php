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
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

class Demo extends Page
{
    protected function pageTitle()
    {
        return 'Demo';
    }
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
        return  Div::init([PageUI::init(
            [
                Button::init()->text('Click Me')->wireClick('alertTest("Nội dung alert")')->className('p-2 mb-2'),
                Input::init('demoName')->date()->label('xin chào'),
                Table::init()
                    ->column('username')
                    ->column('email')
                    ->query(User::query()),

            ]
        )->title($this->pageTitle())->rightUI([
            Input::init('demoName')->date(),
            Button::init()->text('Đăng nhập'),
        ])])
            ->className('container p-2');
    }
}
