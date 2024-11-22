<?php

namespace Sokeio\Models;


class Role extends \Sokeio\Model
{
    private static $roleSupperAdmin = null;
    public static function SupperAdmin()
    {
        if (!self::$roleSupperAdmin) {
            self::$roleSupperAdmin = 'supper_admin';
        }
        return self::$roleSupperAdmin;
    }
    protected $fillable = ['*'];
    public function isActive()
    {
        return $this->status == 1;
    }
    public function isBlock()
    {
        return !$this->isActive();
    }
    public function isSuperAdmin(): bool
    {
        return $this->slug == self::SupperAdmin();
    }
    public function permissions()
    {
        return $this->belongsToMany(config('sokeio.model.permission'), 'roles_permissions');
    }
    public function getPermissionIdsAttribute()
    {
        return  $this->permissions()->get()->map(function ($item) {
            return $item->id;
        })->toArray();
    }
    public function users()
    {
        return $this->belongsToMany(config('sokeio.model.user'), 'users_roles');
    }
}
