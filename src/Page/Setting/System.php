<?php

namespace Sokeio\Page\Setting;

use Livewire\Attributes\Rule;
use Sokeio\Platform;
use Sokeio\Support\Livewire\PageConfig;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\DatePicker;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\Tab\TabControl;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'System Information', menuTitle: 'System', menu: true)]
class System extends \Sokeio\Page
{

    use WithUI;
    #[Rule('required')]
    public $name = '';
    public function saveData()
    {
        $this->validate();
        $this->sokeioClose();
        $this->refreshRef();
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                TabControl::init()
                    ->tabItemView('demo')
                    ->tabItemComponent('demo2', 'ti ti-dashboard', 'sokeio::demo1.index')
                    ->vertical()
            ])->title($this->getPageConfig()->getTitle())
                
                ->className('p-2')

        ];
    }
}
