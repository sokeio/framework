<?php

namespace BytePlatform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group', 'slug'];

    public function roles()
    {
        return $this->belongsToMany(config('byte.model.role'), 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(config('byte.model.user'), 'users_permissions');
    }
}
