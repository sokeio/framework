<?php

namespace SokeioTheme\Admin\Page\Setting;

use Sokeio\Attribute\PageInfo;
use Sokeio\Marketplate;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'License',
    menu: true,
    menuTitle: 'License',
    icon: 'ti ti-license',
    sort: 999999999,
)]
class License extends \Sokeio\Page
{
    use WithUI;
    public $licenseKey = "";
    public $licenseInfo = "";
    public $product = [];
    public $isLicensed = false;
    public function mount()
    {
        $this->product = Marketplate::getProductInfo();
        $this->licenseInfo = Marketplate::getLicense();
        if ($this->licenseInfo && isset($this->licenseInfo['key'])) {
            $this->licenseKey = $this->licenseInfo['key'];
            $this->isLicensed = true;
        }
    }
    public function checkLicense()
    {
        $this->isLicensed = Marketplate::verifyLicense($this->licenseKey, data_get($this->product, 'id'));

        if ($this->isLicensed) {
            $this->licenseInfo = Marketplate::getLicense();
            $this->alert(__('License Activated Successfully'), 'License Activation', 'success');
        } else {
            $this->alert(__('License Activation Failed'), 'License Activation', 'error');
        }
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Card::make([
                    Div::make([
                        Div::make()->viewBlade('sokeio::pages.setting.license.product'),
                        Div::make([
                            Input::make('licenseKey')
                                ->label(__('License Key'))
                                ->placeholder('License Key'),
                            Div::make([
                                Button::make()->text(__('Activate'))
                                    ->className('btn btn-success mt-3')
                                    ->icon('ti ti-check')
                                    ->wireClick(function (Button $button) {
                                        $this->checkLicense();
                                    })
                            ])->className('text-center'),
                        ])->when(function () {
                            return !$this->isLicensed;
                        })
                    ])->container()
                        ->className('')
                ])->hideSwitcher()
                    ->title(__('License Information'))
            ])->rightUI([])
                ->hidePageHeader()

        ];
    }
}
