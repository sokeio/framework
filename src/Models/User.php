<?php

namespace Sokeio\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Sokeio\Concerns\WithModelHook;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Sokeio\Concerns\WithPermission;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use SoftDeletes;
    use WithPermission;
    use WithModelHook;
    protected $fillable = ["*"];
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
    public function getPermissionIdsAttribute()
    {
        return  $this->permissions()->get()->map(function ($item) {
            return $item->id;
        })->toArray();
    }
    public function getRoleIdsAttribute()
    {
        return  $this->roles()->get()->map(function ($item) {
            return $item->id;
        })->toArray();
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
