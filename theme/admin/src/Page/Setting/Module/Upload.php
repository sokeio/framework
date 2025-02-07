<?php

namespace SokeioTheme\Admin\Page\Setting\Module;

use Sokeio\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\UploadFile;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(admin: true, auth: true,  title: 'Upload Module (Not Implemented)')]
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
            PageUI::make([
                UploadFile::make('fileUpload'),
            ])
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x'),
                        Button::make()->label(__('Upload'))->wireClick('saveData')->icon('ti ti-upload')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
