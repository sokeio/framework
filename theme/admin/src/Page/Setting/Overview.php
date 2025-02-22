<?php

namespace SokeioTheme\Admin\Page\Setting;

use Sokeio\Core\Attribute\AdminPageInfo;
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

#[AdminPageInfo(
    title: 'Overview',
    menu: true,
    menuTitle: 'Overview',
    menuIcon: 'ti ti-table-options',
    menuTargetSort: 99999,
    icon: 'ti ti-table-options',
    sort: -100,
)]
class Overview extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_OVERVIEW_SETTING_PAGE";
    use WithSettingUI;
    private function settingOverview()
    {
        return SettingUI::make([
            Input::make('SOKEIO_SYSTEM_NAME')->col4()
                ->label('System Name')
                ->ruleRequired('Please enter system name')
                ->placeholder('System Name')
                ->valueDefault('Sokeio Technology'),
            MediaFile::make('SOKEIO_SYSTEM_LOGO')->col4()
                ->label('System Logo'),
            ContentEditor::make('SOKEIO_SYSTEM_DESCRIPTION')
                ->col12()
                ->label('System Description')
                // ->ruleRequired('Please enter system description')
                ->placeholder('System Description'),
        ])
            ->bodyRow()
            ->title('Overview Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->prefix('formData.overview')
            ->className('mb-3');
    }
    private function settingAdmin()
    {
        return SettingUI::make([
            Select::make('SOKEIO_LAYOUT_ADMIN_THEME')
                ->col3()
                ->label('Admin Theme')
                ->valueDefault('default')
                ->dataSource(Theme::getThemeAdmin()->getLayouts()),
            Select::make('SOKEIO_ADMIN_HEADER_CONTAINER')
                ->col3()
                ->label('Header Container')
                ->valueDefault(setting('SOKEIO_ADMIN_HEADER_CONTAINER', 'container-xxl'))
                ->dataSource([
                    ['value' => 'container-fluid', 'text' => 'Container Fluid'],
                    ['value' => 'container', 'text' => 'Container'],
                    ['value' => 'container-xxl', 'text' => 'Container XXL'],
                ]),
            Select::make('SOKEIO_ADMIN_BODY_CONTAINER')
                ->col3()
                ->label('Body Container')
                ->valueDefault(setting('SOKEIO_ADMIN_BODY_CONTAINER', 'container-xxl'))
                ->dataSource([
                    ['value' => 'container-fluid', 'text' => 'Container Fluid'],
                    ['value' => 'container', 'text' => 'Container'],
                    ['value' => 'container-xxl', 'text' => 'Container XXL'],
                ]),
            SwitchField::make('SOKEIO_ADMIN_HEADER_STICKY_ENABLE')
                ->col3()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Admin Sticky Header')
                ->valueDefault(true),
            SwitchField::make('SOKEIO_SYSTEM_UPDATER_ENABLE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('System Updater'),
            SwitchField::make('SOKEIO_ADMIN_REGISTRATION_ENABLE_PAGE')
                ->col6()

                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Register User Page')
                ->valueDefault(true),
            MediaFile::make('SOKEIO_ADMIN_LOGIN_COVER_IMAGE')
                ->col12()
                ->multiple()
                ->label('Auth Cover Image'),

        ])
            ->bodyRow()
            ->title('Admin Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->prefix('formData.admin')
            ->className('mb-3');
    }
    private function settingUtility()
    {
        return SettingUI::make([
            SwitchField::make('SOKEIO_SHOW_ICON_IN_SUBMENU')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('Show Icon In Submenu'),
            SwitchField::make('SOKEIO_SHOW_PROGRESS_TIMER')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Progress Timer'),
            SwitchField::make('SOKEIO_SHOW_POSITION_DEBUG')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Position Debug(Only Admin)'),
            SwitchField::make('PLATFORM_TABLE_ROUTE_ENABLE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('Show Route Table(DEBUG)'),

        ])->title('Develop Utility')
            ->bodyRow()
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->prefix('formData.utility')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                $this->settingOverview(),
                $this->settingAdmin(),
                $this->settingUtility(),
            ])->childWithKey(self::KEY_UI)->row()->rightUI([
                Button::make()
                    ->className('btn btn-primary')
                    ->label('Save Setting')
                    ->icon('ti ti-settings')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-login')

        ];
    }
}
