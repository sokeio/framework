<?php

namespace ByteTheme\Admin\Livewire\Auth;

use BytePlatform\Component;
use Illuminate\Support\Facades\Auth;

class Signup extends Component
{
    public $email;
    public $name;
    public $password;
    public $agree;
    protected $rules = [
        'password' => 'required|min:6',
        'name' => 'required|min:6',
        'email' => 'required|min:6',
        'agree' => 'required',
    ];
    public function DoWork()
    {
        $this->validate();
        $user = new (config('byte.model.user'));
        $user->email = $this->email;
        $user->name = $this->name;
        $user->password = $this->password;
        $user->save();
        return redirect(route('admin.login'));
    }
    public function mount()
    {
    }
    public function render()
    {
        page_title('Sigup account');

        return view('theme::auth.sign-up');
    }
}
