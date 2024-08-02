<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;

class Page extends Component
{
    protected static function RouteName()
    {
        return 'page-detail';
    }
    protected static function RouteUrl()
    {
        return '/page-demo';
    }
    public static function RoutePage()
    {
        RouteEx::web(function () {
            Route::get(static::RouteUrl(), static::class)->name(static::RouteName());
        });
    }
}
