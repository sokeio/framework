<?php

namespace SokeioTheme\Admin\Livewire\Auth;

use Sokeio\Component;
use Sokeio\Facades\Assets;
use Illuminate\Support\Facades\Auth;

class ForgotPassword extends Component
{
    public $username;
    public $password;
    public $isRememberMe;

    protected $rules = [
        'password' => 'required|min:1',
        'username' => 'required|min:1',
    ];
    public function doWork()
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->username, 'password' => $this->password], $this->isRememberMe)) {
            return redirect('/');
        } else {
            $this->showMessage("Login Fail");
        }
    }
    public function mount()
    {
        Assets::setTitle(__('Forgot password'));
    }
    public function render()
    {
        return view('theme::auth.forgot-password');
    }
}
