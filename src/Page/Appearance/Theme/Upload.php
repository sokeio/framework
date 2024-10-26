<?php

namespace Sokeio\Page\Appearance\Theme;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\UploadFile;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Upload Theme ')]
class Upload extends \Sokeio\Page
{
    use WithUI;
    public $fileUpload;
    public function saveData()
    {
        $this->sokeioClose();
    }
    protected function setupUI()
    {
        return [
            ModalUI::init([
                UploadFile::init('fileUpload'),
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x'),
                        Button::init()->text(__('Upload'))->wireClick('saveData')->icon('ti ti-upload')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
