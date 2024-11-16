<?php

namespace Sokeio\Page\Setting;

use Sokeio\Setting;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaFile;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\PageUI;
use Sokeio\UI\SettingUI;
use Sokeio\UI\WithSettingUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Overview',
    menu: true,
    menuTitle: 'Overview',
    icon: 'ti ti-cogs',
    sort: 0,
)]
class Overview extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_OVERVIEW_SETTING_PAGE";
    use WithSettingUI;
    private function settingOverview()
    {
        return SettingUI::init([
            Input::init('SOKEIO_SYSTEM_NAME')->col6()
                ->label('System Name')
                ->ruleRequired('Please enter system name')
                ->placeholder('System Name')
                ->valueDefault('Sokeio Technology'),
            MediaFile::init('system_logo')->col6()
                ->label('System Logo')
                ->keyInSetting('SOKEIO_SYSTEM_LOGO'),
            ContentEditor::init('SOKEIO_SYSTEM_DESCRIPTION')
                ->col12()
                ->label('System Description')
                // ->ruleRequired('Please enter system description')
                ->placeholder('System Description'),
        ])
            ->bodyRow()
            ->title('Overview Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->setPrefix('formData.overview')
            ->className('mb-3');
    }
    private function settingUtility()
    {
        return SettingUI::init([
            SwitchField::init('SOKEIO_SHOW_PROGRESS_TIMER')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Progress Timer'),
            SwitchField::init('SOKEIO_SHOW_POSITION_DEBUG')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Position Debug(Only Admin)'),
        ])->title('Utility Setting')
            ->bodyRow()
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->setPrefix('formData.utility')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                $this->settingOverview(),
                $this->settingUtility(),
                ...Setting::getUI(self::KEY_UI)
            ])->row()->rightUI([
                Button::init()
                    ->className('btn btn-primary')
                    ->text('Save Setting')
                    ->icon('ti ti-settings')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-login')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
