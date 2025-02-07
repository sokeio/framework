<?php

namespace SokeioTheme\Admin\Page\Appearance\Theme;

use Sokeio\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\UploadFile;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(admin: true, auth: true,  title: 'Marketplace Theme (Not Implemented)')]
class Marketplace extends \Sokeio\Page
{
    use WithUI;
    public function saveData()
    {
        $this->sokeioClose();
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Div::make()->className('mt-auto')->viewBlade('sokeio::pages.appearance.theme.marketplace')
            ])
                
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])
                ->icon('ti ti-building-store')
                ->xxlSize()

        ];
    }
}
