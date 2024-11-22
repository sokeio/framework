<?php

namespace Sokeio\Page\Setting;

use Sokeio\Platform;
use Sokeio\Setting;
use Sokeio\Attribute\PageInfo;
use Sokeio\Theme;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaFile;
use Sokeio\UI\Field\Select;
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
    menuTargetSort: 99999,
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
            Input::init('SOKEIO_SYSTEM_NAME')->col4()
                ->label('System Name')
                ->ruleRequired('Please enter system name')
                ->placeholder('System Name')
                ->valueDefault('Sokeio Technology'),
            MediaFile::init('SOKEIO_SYSTEM_LOGO')->col4()
                ->label('System Logo'),
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
    private function settingAdmin()
    {
        return SettingUI::init([
            Select::init('SOKEIO_LAYOUT_ADMIN_THEME')
                ->col4()
                ->label('Admin Theme')
                ->valueDefault('default')
                ->dataSource(Theme::getThemeAdmin()->getLayouts()),
            SwitchField::init('SOKEIO_ADMIN_HEADER_STICKY_ENABLE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Admin Sticky Header')
                ->valueDefault(true),
            SwitchField::init('SOKEIO_SYSTEM_UPDATER_ENABLE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('System Updater'),
            SwitchField::init('SOKEIO_ADMIN_REGISTRATION_ENABLE_PAGE')
                ->col6()

                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Register User Page')
                ->valueDefault(true),
            MediaFile::init('SOKEIO_ADMIN_LOGIN_COVER_IMAGE')
                ->col12()
                ->multiple()
                ->label('Auth Cover Image'),

        ])
            ->bodyRow()
            ->title('Admin Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->setPrefix('formData.admin')
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
            SwitchField::init('PLATFORM_TABLE_ROUTE_ENABLE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('Show Route Table(DEBUG)'),

        ])->title('Develop Utility')
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
                $this->settingAdmin(),
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

        ];
    }
}
