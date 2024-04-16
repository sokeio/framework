<?php

namespace SokeioTheme\Blog\Livewire\Auth;

use Sokeio\Component;
use Sokeio\Facades\Assets;

class Signup extends Component
{
    public $email;
    public $name;
    public $password;
    public $agree;
    protected $rules = [
        'password' => 'required|min:6',
        'name' => 'required|min:6',
        'email' => 'required|email:rfc,dns|unique:users,email',
        'agree' => 'required',
    ];
    public function doWork()
    {
        $this->validate();
        $user = new (config('sokeio.model.user'));
        $user->email = $this->email;
        $user->name = $this->name;
        $user->password = $this->password;
        $user->status = 1;
        $user->save();
        if ($role = env('SOKEIO_SIGUP_ROLE_DEFAULT')) {
            $role =   (config('sokeio.model.role'))::where('slug', $role)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
        return redirect(route('site.login'));
    }
    public function mount()
    {
        Assets::setTitle(__('Sigup account'));
    }
    public function render()
    {
        return viewScope('theme::auth.sign-up');
    }
}
