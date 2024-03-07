<?php

namespace Sokeio\Middleware;

use Sokeio\Facades\Theme;

class ThemeAdmin
{
    public function handle($request, \Closure $next, $theme = '')
    {
        if ($theme !== '') {
            Theme::setLayout($theme);
        }
        return $next($request);
    }
}
