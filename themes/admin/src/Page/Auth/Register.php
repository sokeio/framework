<?php

namespace SokeioTheme\Admin\Page\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: false, url: '/register', route: 'register', layout: 'conver', title: 'Register')]
class Register extends \Sokeio\Page
{
    #[Rule('required')]
    public $name;
    #[Rule('required|unique:users,email')]
    public $email;
    #[Rule('required')]
    public $password;
    public function register()
    {
        $this->validate();
        $user = new (config('sokeio.model.user'));
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->save();
        return redirect('/login');
    }
    public function render()
    {
        return Theme::view('pages.auth.register');
    }
}
