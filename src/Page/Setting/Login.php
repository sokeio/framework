<?php

namespace Sokeio\Page\Setting;

use Sokeio\Components\Field\SelectField;
use Sokeio\Setting;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Select;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\SettingUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Login Setting',
    menu: true,
    menuTitle: 'Login Setting',
    icon: 'ti ti-login',
    sort: 100,
)]
class Login extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_LOGIN_SETTING_PAGE";
    use WithUI;
    public const COLUMN_GROUP = 'col-sm-12 col-md-12 col-lg-12';
    public const COLUMN_GROUP2 = 'col-sm-12 col-md-6 col-lg-6';
    public $formData = [];

    public function saveData()
    {
        $this->getUI()->saveInSetting();
        $this->alert('Setting has been saved!');
    }
    public function mount()
    {
        $this->getUI()->loadInSetting();
    }
    private function settingLogin()
    {
        return SettingUI::init([
            // SwitchField::init('login_view_visiable')
            //     ->labelTrue('Enable')
            //     ->labelFalse('Disable')
            //     ->label('Login View Visiable')
            //     ->valueDefault(true)
            //     ->keyInSetting('SOKEIO_LOGIN_VIEW_VISIABLE'),
            Select::init('auth_type')->options([
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
            ])->label('Auth Type')->valueDefault('email')->keyInSetting('SOKEIO_AUTH_WITH_TYPE'),
            ContentEditor::init('message_login')
                ->label('Login Message')
                ->ruleRequired('Please enter login message')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.login.enable');
                })
                ->keyInSetting('SOKEIO_LOGIN_MESSAGE'),
        ])
            ->title('Login Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->showSwitcher('SOKEIO_LOGIN_ENABLE')
            ->setPrefix('formData.login')
            ->className('mb-3');
    }
    private function settingLoginWithGoogle()
    {
        return SettingUI::init([
            Input::init('google_client_id')
                ->label('Google Client ID')
                ->ruleRequired('Please enter google client id')
                ->keyInSetting('SOKEIO_GOOGLE_CLIENT_ID')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.google.enable');
                }),
            Input::init('google_client_secret')
                ->label('Google Client Secret')
                ->ruleRequired('Please enter google client secret')
                ->keyInSetting('SOKEIO_GOOGLE_CLIENT_SECRET')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.google.enable');
                }),
            Input::init('google_redirect')
                ->label('Google Redirect')
                ->ruleRequired('Please enter google redirect')
                ->keyInSetting('SOKEIO_GOOGLE_REDIRECT')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.google.enable');
                }),
        ])
            ->title('Login With Google Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GOOGLE_ENABLE', false)
            ->setPrefix('formData.google')
            ->className('mb-3');
    }
    private function settingLoginWithFacebook()
    {
        return SettingUI::init([
            Input::init('facebook_client_id')
                ->label('Facebook Client ID')
                ->ruleRequired('Please enter facebook client id')
                ->keyInSetting('SOKEIO_FACEBOOK_CLIENT_ID')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.facebook.enable');
                }),
            Input::init('facebook_client_secret')
                ->label('Facebook Client Secret')
                ->ruleRequired('Please enter facebook client secret')
                ->keyInSetting('SOKEIO_FACEBOOK_CLIENT_SECRET')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.facebook.enable');
                }),
            Input::init('facebook_redirect')
                ->label('Facebook Redirect')
                ->ruleRequired('Please enter facebook redirect')
                ->keyInSetting('SOKEIO_FACEBOOK_REDIRECT')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.facebook.enable');
                }),
        ])
            ->title('Login With Facebook Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_FACEBOOK_ENABLE', false)
            ->setPrefix('formData.facebook')
            ->className('mb-3');
    }
    private function settingLoginWithGithub()
    {
        return SettingUI::init([
            Input::init('github_client_id')
                ->label('Github Client ID')
                ->ruleRequired('Please enter github client id')
                ->keyInSetting('SOKEIO_GITHUB_CLIENT_ID')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.github.enable');
                }),
            Input::init('github_client_secret')
                ->label('Github Client Secret')
                ->ruleRequired('Please enter github client secret')
                ->keyInSetting('SOKEIO_GITHUB_CLIENT_SECRET')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.github.enable');
                }),
            Input::init('github_redirect')
                ->label('Github Redirect')
                ->ruleRequired('Please enter github redirect')
                ->keyInSetting('SOKEIO_GITHUB_REDIRECT')
                ->whenRule(function ($item) {
                    return $item->getWireValue('formData.github.enable');
                }),
        ])
            ->title('Login With Github Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP2)
            ->showSwitcher('SOKEIO_GITHUB_ENABLE', false)
            ->setPrefix('formData.github')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                $this->settingLogin(),
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
                ->icon('ti ti-login')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
