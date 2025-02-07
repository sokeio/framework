<?php

namespace SokeioTheme\Admin\Page\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Sokeio\Attribute\AdminPageInfo;
use Sokeio\Theme;

#[AdminPageInfo(
    admin: true,
    auth: false,
    url: '/register',
    route: 'register',
    layout: 'conver',
    title: 'Register',
    enableKeyInSetting: 'SOKEIO_ADMIN_REGISTRATION_ENABLE_PAGE'
)]
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
        DB::transaction(function () {
            $user = new (config('sokeio.model.user'));
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->save();
            if ($user->id === 1) {
                // Check if user is super admin
                $role = (config('sokeio.model.role'))::where('slug', (config('sokeio.model.role'))::SupperAdmin())
                    ->first();
                if (!$role) {
                    $role = new (config('sokeio.model.role'));
                    $role->name = 'Super Admin';
                    $role->slug = (config('sokeio.model.role'))::SupperAdmin();
                    $role->is_active = true;
                    $role->save();
                }

                $user->roles()->sync([$role->id]);
            }
        });

        $this->refreshPage(route('admin.login'));
    }
    public function render()
    {
        return Theme::view('pages.auth.register');
    }
}
