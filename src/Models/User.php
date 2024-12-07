<?php

namespace Sokeio\Models;

use Sokeio\Concerns\WithModelHook;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Sokeio\Concerns\WithPermission;
use Illuminate\Support\Facades\Hash;
use Sokeio\Attribute\ModelInfo;

#[ModelInfo(createBy: '', updateBy: '')]
class User extends Authenticatable
{
    use WithPermission;
    use WithModelHook;
    protected $fillable = ["*"];
    protected $hidden = ["password"];
    public function isActive()
    {
        return $this->status == 1;
    }
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('sokeio.model.role')::SupperAdmin());
    }
    public function isBlock()
    {
        return !$this->isActive();
    }
    public function getAllPermission()
    {
        $permissions = $this->permissions()->pluck('slug')->toArray();
        // get permissions in roles of user
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissions()->pluck('slug')->toArray());
        }
        return collect($permissions)->unique()->toArray();
    }
    public function getAllRole()
    {
        $roles = $this->roles->pluck('slug')->toArray();
        return collect($roles)->unique()->toArray();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
        self::updating(function ($model) {
            if ($model->password && Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
    }
}
