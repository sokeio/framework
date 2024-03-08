<?php

namespace Sokeio\Middleware;

use Sokeio\Facades\Locale;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Theme;

class SokeioWeb
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        Platform::cleanThemeAdmin();
        Locale::setLocaleApp();
        Theme::reTheme();
        Theme::setupOption();
        Platform::bootGate();
        Platform::doReady();
        Platform::doReadyAfter();
        return $next($request);
    }
}
