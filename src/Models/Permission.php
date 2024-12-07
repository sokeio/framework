<?php

namespace Sokeio\Models;

use Sokeio\Attribute\ModelInfo;

#[ModelInfo()]
class Permission extends \Sokeio\Model
{
    protected $fillable = ['name', 'group', 'slug'];

    public function roles()
    {
        return $this->belongsToMany(config('sokeio.model.role'), 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(config('sokeio.model.user'), 'users_permissions');
    }
}
