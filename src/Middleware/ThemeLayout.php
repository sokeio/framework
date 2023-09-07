<?php

namespace BytePlatform\Middleware;

use BytePlatform\Facades\Theme;

class ThemeLayout
{
    public function handle($request, \Closure $next, $theme = '')
    {
        if ($theme != '')
            Theme::setLayout($theme);
        return $next($request);;
    }
}
