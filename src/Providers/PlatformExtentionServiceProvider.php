<?php

namespace Sokeio\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Sokeio\ColectionPaginate;
use Sokeio\Directives\PlatformBladeDirectives;

class PlatformExtentionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Blade directives
        Blade::directive('ThemeBody', [PlatformBladeDirectives::class, 'themeBody']);
        Blade::directive('ThemeHead', [PlatformBladeDirectives::class, 'themeHead']);
        Blade::directive('role',  [PlatformBladeDirectives::class, 'role']);
        Blade::directive('endrole', [PlatformBladeDirectives::class, 'endRole']);
        Blade::directive('permission',  [PlatformBladeDirectives::class, 'permission']);
        Blade::directive('endPermission', [PlatformBladeDirectives::class, 'endPermission']);
        Collection::macro('paginate', function ($pageSize) {
            /** @var \Illuminate\Support\Collection $collection */
            $collection = $this;
            return ColectionPaginate::paginate($collection, $pageSize);
        });
        Blueprint::macro('byUser', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table = $this;
            $table->interger('created_by')->nullable();
            $table->interger('updated_by')->nullable();
        });
    }
}
