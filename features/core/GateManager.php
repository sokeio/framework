<?php

namespace Sokeio\Core;

use Illuminate\Support\Facades\Schema;
use Sokeio\Models\Permission;
use Sokeio\Models\UserAccessToken;
use Sokeio\Pattern\Singleton;

class GateManager
{
    use Singleton;
    private $user;

    private $roles = [];
    private $permissions = [];
    private $hooks = [];
    private $loaders = [];
    public function filter(callable $filter)
    {
        if (!$filter) {
            return;
        }
        $this->hooks[] = $filter;
    }
    public function applyFilter($user, $init = null)
    {

        foreach ($this->hooks as $hook) {
            if (!is_callable($hook)) {
                continue;
            }
            $init = call_user_func($hook, $user, $init);
        }
        return $init;
    }
    public function register($permission, $name, $module)
    {
        $this->loaders[$permission] = [
            'permission' => $permission,
            'name' => $name,
            'module' => $module
        ];
    }
    public function getLoaders()
    {
        return $this->loaders;
    }
    public function getUserId()
    {
        return $this->user?->id;
    }
    public function getUserInfo()
    {
        return $this->user;
    }
    public function isSupperAdmin()
    {
        return in_array(config('sokeio.model.role')::SupperAdmin(), $this->roles);
    }
    public function setUser($user)
    {
        $this->user = $user;
        $this->permissions = $user->getAllPermission();
        $this->roles = $user->getAllRole();
        return $this;
    }

    public function getUserByToken($token)
    {
        return app(UserAccessToken::class)->findToken($token)?->tokenable;
    }
    public function getPermission()
    {
        return $this->permissions;
    }
    public function getRole()
    {
        return $this->roles;
    }
    public function check($permssion)
    {
        if ($this->isSupperAdmin()) {
            return true;
        }
        return
            str_starts_with($permssion, '_')
            || in_array($permssion, $this->ignores)
            || in_array($permssion, $this->permissions);
    }
    public function role($role)
    {
        if ($this->isSupperAdmin()) {
            return true;
        }
        return in_array($role, $this->roles);
    }
    private function checkPermissionName($permssion)
    {
        return $permssion
            && !str_starts_with($permssion, '_')
            && (count($this->ignores) == 0 || !in_array($permssion, $this->ignores));
    }
    public function updatePermission()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();
        $loaders = $this->loaders;
        ksort($loaders);
        foreach ($loaders as $item) {
            [
                'permission' => $permission,
                'name' => $name,
                'module' => $module
            ] = $item;
            Permission::query()->create([
                'name' => $name,
                'group' => $module,
                'slug' => $permission
            ]);
        }
    }
}
