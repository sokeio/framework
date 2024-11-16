<?php

namespace SokeioTheme\Admin\Page\Auth;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(
    admin: true,
    auth: false,
    url: '/forgot-password',
    route: 'forgot-password',
    layout: 'none',
    title: 'Forgot Password',
    enableKeyInSetting: 'SOKEIO_ADMIN_FORGOT_PASSWORD_ENABLE_PAGE'
)]
class ForgotPassword extends \Sokeio\Page
{
    public function doWork(){
        $this->alert('Please check your email to reset password', 'Forgot Password', 'success');
    }
    public function render()
    {
        return Theme::view('pages.auth.forgot-password');
    }
}
