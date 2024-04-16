<?php

namespace SokeioTheme\Blog\Livewire\Auth;

use Sokeio\Component;
use Illuminate\Support\Facades\Auth;
use Sokeio\Facades\Assets;

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
        return viewScope('theme::auth.forgot-password');
    }
}
