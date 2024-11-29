<?php

namespace Sokeio\Page\Setting\Module;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: ' Module Info')]
class Info extends \Sokeio\Page
{
    use WithUI;
    #[Url(as: 'id')]
    public $dataId;
    protected function setupUI()
    {
        $item = Platform::module()->find($this->dataId);
        $title = $item->getTitle();
        if ($item->isVendor()) {
            $title =  $title . '(Vendor)';
        }
        return [
            PageUI::init([
                Div::init()->className('mt-auto')->viewBlade(
                    'sokeio::livewire.platform.info',
                    [
                        'item' => $item
                    ]
                )
            ])->title($title)
                ->className('p-2')
                ->xlSize()
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Activate'))->icon('ti ti-checks')
                            ->wireClick(function () use ($item) {
                                $item->setActive();
                                $this->sokeioClose();
                                $this->refreshRef();
                            }, 'btn_module_activate')
                            ->when(function ()  use ($item) {
                                return ! $item->isActive();
                            }),
                        Button::init()->text(__('Block'))->icon('ti ti-lock')
                            ->className('btn btn-danger')
                            ->wireClick(function () use ($item) {
                                $item->block();
                                $this->sokeioClose();
                                $this->refreshRef();
                            }, 'btn_module_lock')
                            ->when(function ()  use ($item) {
                                return  $item->isActive();
                            })
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                        ->when(function () use ($item) {
                            return !$item->isVendor();
                        })
                ])

        ];
    }
}
