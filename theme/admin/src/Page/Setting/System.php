<?php

namespace SokeioTheme\Admin\Page\Setting;

use Livewire\Attributes\Rule;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\PageUI;
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
                // TabControl::init()
                //     ->tap(function (TabControl $tabControl) {
                //         foreach (Setting::getTabUIs() as $key => $ui) {
                //             $tabControl->tabItem($key);
                //         }
                //     })
                //     ->vertical()
            ])->title($this->getPageConfig()->getTitle())

                ->className('p-2')

        ];
    }
}
