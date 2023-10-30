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
        'email' => 'required|email:rfc,dns|unique:users,email',
        'agree' => 'required',
    ];
    public function DoWork()
    {
        $this->validate();
        $user = new (config('byte.model.user'));
        $user->email = $this->email;
        $user->name = $this->name;
        $user->password = $this->password;
        $user->status = 1;
        $user->save();
        if ($role = env('BYTE_SIGUP_ROLE_DEFAULT')) {
            $role =   (config('byte.model.role', \BytePlatform\Models\Role::class))::where('slug', $role)->first();
            if ($role)
                $user->roles()->sync([$role->id]);
        }
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
