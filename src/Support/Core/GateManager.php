<?php

namespace BytePlatform\Support\Core;

use Illuminate\Support\Facades\Gate;

class GateManager
{
    private $gateIgnores = [];
    private $gateCustomers = [];
    public function Check()
    {
        $numArgs = func_get_args();
        if (count($numArgs) < 1) return false;
        return Gate::check($numArgs[0], array_shift($numArgs));
    }
    public function BootApp()
    {
        $this->gateIgnores = apply_filters(PLATFORM_PERMISSION_IGNORE, []);
        $this->gateCustomers = apply_filters(PLATFORM_PERMISSION_CUSTOME, []);
        app(config('byte.model.permission', \BytePlatform\Models\Permission::class))->get()->map(function ($permission) {
            Gate::define($permission->slug, function ($user = null) use ($permission) {
                if (!$user) $user = auth()->user();
                if (!apply_filters(PLATFORM_CHECK_PERMISSION, true,  $permission, $user)) return false;
                return $user->hasPermissionTo($permission);
            });
        });
        Gate::before(function ($user, $ability) {
            if (!$user) $user = auth()->user();
            if ($user->isBlock()) return false;
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        foreach ($this->gateCustomers as $permission) {
            Gate::define($permission,  function ($user = null) use ($permission) {
                if (!apply_filters(PLATFORM_CHECK_PERMISSION, true,  $permission, $user)) return false;
                return $user->hasPermissionTo($permission);
            });
        }
        foreach ($this->gateIgnores as $item) {
            Gate::define($item, function () {
                return true;
            });
        }
    }
}
