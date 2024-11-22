<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Sokeio\Models\Permission;

class GateManager
{
    private static $instance;
    public static function getInstance(PlatformManager $manager = null)
    {
        if (!self::$instance && $manager) {
            self::$instance = new self($manager);
        }
        return self::$instance;
    }
    private $user;
    private function __construct(protected PlatformManager $manager) {}
    private $ignores = ['admin.dashboard', 'admin.dashboard.setting'];
    private $customes = [];
    private $roles = [];
    private $permissions = [];
    public function setIgnores($ignores)
    {
        $this->ignores = array_merge($this->ignores, $ignores);
        return $this;
    }
    public function setCustomes($customes)
    {
        $this->customes = array_merge($this->customes, $customes);
        return $this;
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
        // dd([$this->permissions, $this->roles]);
        return $this;
    }

    public function getUserByToken($token)
    {
        //TODO: get user by token {$token}
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
        $listRoutes = Route::getRoutes()->getRoutes();
        foreach ($listRoutes as $item) {
            $name = $item->getName();
            $middlewares = $item->gatherMiddleware();
            if (
                !$middlewares ||
                !$this->checkPermissionName($name)
            ) {
                continue;
            }
            if (in_array('sokeio.admin', $middlewares)) {
                $group = explode('.', $name)[1];
                $group = str($group)->replace('-page', '')->replace('theme-admin', 'sokeio')->value();
                if ($group == 'sokeio') {
                    $group = 'sokeio-system';
                }
                Permission::query()->create([
                    'name' => $name,
                    'group' => $group,
                    'slug' => $name
                ]);
            }
        }
        foreach ($this->customes as $name) {
            if ($this->checkPermissionName($name)) {
                Permission::query()->create([
                    'name' => $name,
                    'group' => $name,
                    'slug' => $name
                ]);
            }
        }
    }
}
