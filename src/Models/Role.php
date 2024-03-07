<?php

namespace Sokeio\Models;

use Sokeio\Concerns\WithSlug;

class Role extends \Sokeio\Model
{
    use WithSlug;
    private static $roleSupperAdmin = null;
    public static function SupperAdmin()
    {
        if (!self::$roleSupperAdmin) {
            self::$roleSupperAdmin = apply_filters(PLATFORM_ROLE_SUPPER_ADMIN, 'supper_admin');
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
        return $this->name == self::SupperAdmin();
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
