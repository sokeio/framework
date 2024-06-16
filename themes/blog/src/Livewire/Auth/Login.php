<?php

namespace SokeioTheme\Blog\Livewire\Auth;

use Sokeio\Component;
use Illuminate\Support\Facades\Auth;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Theme;

class Login extends Component
{
    public $username;
    public $password;
    public $isRememberMe;
    public $urlRef;

    protected $rules = [
        'password' => 'required|min:1',
        'username' => 'required|min:1',
    ];
    public function doWork()
    {
        $this->showMessage(sokeioIsAdmin() ? "Admin" : "User");
        return;
        $this->validate();
        if (Auth::attempt(['email' => $this->username, 'password' => $this->password], $this->isRememberMe)) {
            return redirect($this->urlRef);
        } else {
            $this->addError('account_error', 'Invalid account or password');
        }
    }
    public function mount()
    {
        $this->urlRef = urldecode(request('ref')) ?? route('home');
        Assets::setTitle(__('Login to your account'));
    }
    public function render()
    {
        return viewScope('theme::auth.login');
    }
}
