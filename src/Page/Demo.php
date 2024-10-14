<?php

namespace Sokeio\Page;

use App\Models\User;
use Sokeio\Page;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, title: 'Demo2332233')]
class Demo extends Page
{
    use WithUI;
    public $demoName = '';

    public function alertTest($demoName)
    {
        $this->alert($this->demoName);
        $this->demoName = $demoName;
    }
    public function setupUI()
    {
        return  PageUI::init(
            [
                Button::init()
                    ->text('Click Me')
                    ->wireClick('alertTest("Nội dung alert")')
                    ->className('p-2 mb-2'),
                Input::init('demoName')->date()->label('xin chào'),
                Table::init()
                    ->column('username')
                    ->column('email')
                    ->query(User::query()),

            ]
        )
            ->title($this->getPageConfig()->getTitle())
            ->rightUI([
                Input::init('demoName')->date()->classNameWrapper('me-2'),
                Button::init()->text('Đăng nhập')->wireClick('alert("Đăng nhập")'),
            ])->className('container-xl');
    }
}
