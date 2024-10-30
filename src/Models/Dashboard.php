<?php

namespace Sokeio\Models;


class Dashboard extends \Sokeio\Model
{
    public $fillable = ['name', 'description', 'widgets', 'is_default', 'is_active', 'is_private', 'user_id'];
    public $casts = [
        'widgets' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'is_private' => 'boolean',
    ];
    public function roles()
    {
        return $this->belongsToMany(config('sokeio.model.role'), 'dashboard_roles');
    }

    public function users()
    {
        return $this->belongsToMany(config('sokeio.model.user'), 'dashboard_users');
    }
}
