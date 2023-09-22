<?php

namespace BytePlatform\Models;


class Permission extends \BytePlatform\Model
{

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
