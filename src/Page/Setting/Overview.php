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
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Overview Setting',
    menu: true,
    menuTitle: 'Overview',
    icon: 'ti ti-login',
    sort: 0,
)]
class Overview extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_OVERVIEW_SETTING_PAGE";
    use WithUI;
    public const COLUMN_GROUP = 'col-sm-12 col-md-12 col-lg-12';
    public const COLUMN_GROUP2 = 'col-sm-12 col-md-6 col-lg-6';
    public $formData = [];

    public function saveData()
    {
        $this->getUI()->saveInSetting();
        $this->alert('Setting has been saved!', 'Setting', 'success');
    }
    public function mount()
    {
        $this->getUI()->loadInSetting();
    }
    private function settingOverview()
    {
        return SettingUI::init([
            Input::init('system_name')
                ->col6()
                ->label('System Name')
                ->ruleRequired('Please enter system name')
                ->placeholder('System Name')
                ->valueDefault('Sokeio Technology')
                ->keyInSetting('SOKEIO_SYSTEM_NAME'),
            MediaFile::init('system_logo')
                ->col6()
                ->label('System Logo')
                ->keyInSetting('SOKEIO_SYSTEM_LOGO'),
            ContentEditor::init('system_description')
                ->col12()
                ->label('System Description')
                // ->ruleRequired('Please enter system description')
                ->placeholder('System Description')
                ->keyInSetting('SOKEIO_SYSTEM_DESCRIPTION'),

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
            MediaFile::init('system_admin_login_cover_image')
                ->col4()
                ->label('System Admin Login Cover Image')
                ->ruleRequired('Please enter system admin login cover image')
                ->keyInSetting('SOKEIO_SYSTEM_ADMIN_LOGIN_COVER_IMAGE'),
            SwitchField::init('show_progress_timer')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Progress Timer')
                ->keyInSetting('SOKEIO_SHOW_PROGRESS_TIMER'),
            SwitchField::init('show_position_debug')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Position Debug(Only Admin)')
                ->keyInSetting('SOKEIO_SHOW_POSITION_DEBUG'),
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
                    ->icon('ti ti-save')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-login')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
