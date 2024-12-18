<?php

namespace Sokeio\Page\Setting;

use Sokeio\Components\Field\SelectField;
use Sokeio\Setting;
use Sokeio\Attribute\PageInfo;
use Sokeio\Models\Role;
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
    sort: -20,
)]
class Authentication extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_LOGIN_SETTING_PAGE";
    use WithSettingUI;
    private function settingLogin()
    {
        return SettingUI::make([
            Select::make('SOKEIO_AUTH_WITH_TYPE')
                ->col3()
                ->dataSource([
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
                Select::make('SOKEIO_ROLE_IN_REGISTER')
                    ->col3()
                    ->remoteActionWithModel(Role::class, 'name')
                    ->label('Register Role'),
            SwitchField::make('SOKEIO_SOCIAL_LOGIN')
                ->col3()
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
            ->prefix('formData.login')
            ->className('mb-3');
    }
    private function settingLoginWithGoogle()
    {
        return SettingUI::make([
            Input::make('SOKEIO_GOOGLE_CLIENT_ID')
                ->label('Google Client ID')
                ->ruleRequired('Please enter google client id'),
            Input::make('SOKEIO_GOOGLE_CLIENT_SECRET')
                ->label('Google Client Secret')
                ->ruleRequired('Please enter google client secret'),
            Input::make('SOKEIO_GOOGLE_REDIRECT')
                ->label('Google Redirect')
                ->valueDefault(route('socialite.callback', ['social' => 'google']))
                ->ruleRequired('Please enter google redirect'),
        ])
            ->title('Login With Google Setting')
            ->icon('ti ti-brand-google')
            ->subtitle('
            <div class=" p-1">
            <div class="alert alert-info">
                <p>For Google login, please enable OAuth in your Google account and copy the Client ID and Client Secret 
                from <a href="https://console.developers.google.com/" target="_blank">Google OAuth</a> in your Google account.</p>
            </div>
            <p class="fw-bold">
            Login with Google: ' . tagLink(route('socialite.login', ['social' => 'google'])) . '
            </p>
            </div>
        ')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GOOGLE_ENABLE', false)
            ->prefix('formData.google')
            ->className('mb-3');
    }
    private function settingLoginWithFacebook()
    {
        return SettingUI::make([
            Input::make('SOKEIO_FACEBOOK_CLIENT_ID')
                ->label('Facebook Client ID')
                ->ruleRequired('Please enter facebook client id'),
            Input::make('SOKEIO_FACEBOOK_CLIENT_SECRET')
                ->label('Facebook Client Secret')
                ->ruleRequired('Please enter facebook client secret'),
            Input::make('SOKEIO_FACEBOOK_REDIRECT')
                ->label('Facebook Redirect')
                ->ruleRequired('Please enter facebook redirect')
                ->valueDefault(route('socialite.callback', ['social' => 'facebook'])),
        ])
            ->title('Login With Facebook Setting')
            ->icon('ti ti-brand-facebook')
            ->subtitle('
            <div class=" p-1">
            <div class="alert alert-info">
                <p>For Facebook login, please enable OAuth in your Facebook account and copy the Client ID and Client Secret 
                from <a href="https://developers.facebook.com/apps" target="_blank">Facebook OAuth</a> in your Facebook account.</p>
            </div>
            <p class="fw-bold">
            Login with Facebook: ' . tagLink(route('socialite.login', ['social' => 'facebook'])) . '
            </p>
            </div>
            ')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_FACEBOOK_ENABLE', false)
            ->prefix('formData.facebook')
            ->className('mb-3');
    }
    private function settingLoginWithGithub()
    {
        return SettingUI::make([
            Input::make('SOKEIO_GITHUB_CLIENT_ID')
                ->label('Github Client ID')
                ->ruleRequired('Please enter github client id'),
            Input::make('SOKEIO_GITHUB_CLIENT_SECRET')
                ->label('Github Client Secret')
                ->ruleRequired('Please enter github client secret'),
            Input::make('SOKEIO_GITHUB_REDIRECT')
                ->label('Github Redirect')
                ->ruleRequired('Please enter github redirect')
                ->valueDefault(route('socialite.callback', ['social' => 'github'])),
        ])
            ->title('Login With Github Setting')
            ->icon('ti ti-brand-github')
            ->subtitle('<div class=" p-1">
            <div class="alert alert-info">
                <p>For Github login, please enable OAuth in your Github account and copy the Client ID and Client Secret from
                <a href="https://github.com/settings/developers" target="_blank">Github OAuth</a> in your Github account.</p>
            </div>
            <p class="fw-bold">
            Login with Github: ' . tagLink(route('socialite.login', ['social' => 'github'])) . '
            </p>
            </div>
        ')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GITHUB_ENABLE', false)
            ->prefix('formData.github')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                $this->settingLogin(),
                $this->settingLoginWithGoogle(),
                $this->settingLoginWithFacebook(),
                $this->settingLoginWithGithub(),
                ...Setting::getUI(self::KEY_UI)
            ])->row()->rightUI([
                Button::make()
                    ->className('btn btn-primary')
                    ->text('Save Setting')
                    ->icon('ti ti-save')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-settings')


        ];
    }
}
