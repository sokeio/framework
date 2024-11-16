<?php

namespace Sokeio\Page\Setting;

use Sokeio\Components\Field\SelectField;
use Sokeio\Setting;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaFile;
use Sokeio\UI\Field\Select;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\SettingUI;
use Sokeio\UI\WithSettingUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Authentication',
    menu: true,
    menuTitle: 'Authentication',
    icon: 'ti ti-user',
    sort: 100,
)]
class Authentication extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_LOGIN_SETTING_PAGE";
    use WithSettingUI;
    private function settingLogin()
    {
        return SettingUI::init([
            Select::init('SOKEIO_AUTH_WITH_TYPE')
                ->col4()
                ->options([
                    [
                        'value' => 'email',
                        'text' => 'Login with Email',
                    ],
                    [
                        'value' => 'phone',
                        'text' => 'Login with Phone',
                    ],
                    [
                        'value' => 'phone_email',
                        'text' => 'Login with Phone or Email',
                    ]
                ])
                ->label('Auth Type')
                ->valueDefault('email'),
            SwitchField::init('SOKEIO_SOCIAL_LOGIN')
                ->col6()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Show Social Login')
                ->valueDefault(true),
        ])
            ->title('Login Setting')
            ->bodyRow()
            ->icon('ti ti-settings-up')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->setPrefix('formData.login')
            ->className('mb-3');
    }
    private function settingLoginWithGoogle()
    {
        return SettingUI::init([
            Input::init('SOKEIO_GOOGLE_CLIENT_ID')
                ->label('Google Client ID')
                ->ruleRequired('Please enter google client id'),
            Input::init('SOKEIO_GOOGLE_CLIENT_SECRET')
                ->label('Google Client Secret')
                ->ruleRequired('Please enter google client secret'),
            Input::init('SOKEIO_GOOGLE_REDIRECT')
                ->label('Google Redirect')
                ->ruleRequired('Please enter google redirect'),
        ])
            ->title('Login With Google Setting')
            ->icon('ti ti-brand-google')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GOOGLE_ENABLE', false)
            ->setPrefix('formData.google')
            ->className('mb-3');
    }
    private function settingLoginWithFacebook()
    {
        return SettingUI::init([
            Input::init('SOKEIO_FACEBOOK_CLIENT_ID')
                ->label('Facebook Client ID')
                ->ruleRequired('Please enter facebook client id'),
            Input::init('SOKEIO_FACEBOOK_CLIENT_SECRET')
                ->label('Facebook Client Secret')
                ->ruleRequired('Please enter facebook client secret'),
            Input::init('SOKEIO_FACEBOOK_REDIRECT')
                ->label('Facebook Redirect')
                ->ruleRequired('Please enter facebook redirect')
                ->keyInSetting('SOKEIO_FACEBOOK_REDIRECT'),
        ])
            ->title('Login With Facebook Setting')
            ->icon('ti ti-brand-facebook')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_FACEBOOK_ENABLE', false)
            ->setPrefix('formData.facebook')
            ->className('mb-3');
    }
    private function settingLoginWithGithub()
    {
        return SettingUI::init([
            Input::init('SOKEIO_GITHUB_CLIENT_ID')
                ->label('Github Client ID')
                ->ruleRequired('Please enter github client id'),
            Input::init('SOKEIO_GITHUB_CLIENT_SECRET')
                ->label('Github Client Secret')
                ->ruleRequired('Please enter github client secret'),
            Input::init('SOKEIO_GITHUB_REDIRECT')
                ->label('Github Redirect')
                ->ruleRequired('Please enter github redirect'),
        ])
            ->title('Login With Github Setting')
            ->icon('ti ti-brand-github')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GITHUB_ENABLE', false)
            ->setPrefix('formData.github')
            ->className('mb-3');
    }
    private function settingAdminAuth()
    {
        return SettingUI::init([
            MediaFile::init('SOKEIO_ADMIN_LOGIN_COVER_IMAGE')
                ->col4()
                ->label('Auth Cover Image'),
            SwitchField::init('SOKEIO_ADMIN_REGISTRATION_ENABLE_PAGE')
                ->col4()
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->label('Register User Page')
                ->valueDefault(true),
        ])
            ->title('Admin Auth Setting')
            ->bodyRow()
            ->icon('ti ti-user')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->setPrefix('formData.admin-auth')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                $this->settingLogin(),
                $this->settingAdminAuth(),
                $this->settingLoginWithGoogle(),
                $this->settingLoginWithFacebook(),
                $this->settingLoginWithGithub(),
                ...Setting::getUI(self::KEY_UI)
            ])->row()->rightUI([
                Button::init()
                    ->className('btn btn-primary')
                    ->text('Save Setting')
                    ->icon('ti ti-save')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-settings')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}