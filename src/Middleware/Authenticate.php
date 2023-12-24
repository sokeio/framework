<?php

namespace Sokeio\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $response = parent::handle($request, $next, ...$guards);
        // Like: users.index
        $route = $request->route()->getName();
        //skip with prexfix '_'
        if (!Str::startsWith($route, '_')) {
            // Hasn't permission
            if (!checkPermission($route)) {
                return abort(403);
            }
        }

        return $response;
    }
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (sokeio_is_admin()) {
                return (apply_filters(SOKEIO_URL_LOGIN, route('admin.login', ['ref' => urlencode($request->url())])));
            } else {
                return (apply_filters(SOKEIO_URL_LOGIN, route('auth.login', ['ref' => urlencode($request->url())])));
            }
        }
    }
}
