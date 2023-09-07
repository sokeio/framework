<?php

namespace ByteTheme\Admin\Livewire\Auth;

use BytePlatform\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $username;
    public $password;
    public $isRememberMe;

    protected $rules = [
        'password' => 'required|min:1',
        'username' => 'required|min:1',
    ];
    public function DoWork()
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->username, 'password' => $this->password], $this->isRememberMe)) {
            return redirect(route('admin.dashboard'));
        } else {
            $this->addError('account_error', 'Invalid account or password');
        }
    }
    public function mount()
    {
    }
    public function render()
    {
        page_title('Login to your account');
        return view('theme::auth.login');
    }
}
