<?php

namespace Sokeio\Models;


class Dashboard extends \Sokeio\Model
{
    public function roles()
    {
        return $this->belongsToMany(config('sokeio.model.role'), 'dashboard_roles');
    }

    public function users()
    {
        return $this->belongsToMany(config('sokeio.model.user'), 'dashboard_users');
    }
}
