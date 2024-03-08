<?php

namespace Sokeio\Middleware;

use Sokeio\Facades\Locale;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Theme;
use Sokeio\WatchTime;

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
        WatchTime::start();
        Platform::cleanThemeAdmin();
        Locale::setLocaleApp();
        WatchTime::logTime(true, 'locale');
        Theme::reTheme();
        Theme::setupOption();
        WatchTime::logTime(true, 'theme');
        Platform::bootGate();
        WatchTime::logTime(true, 'gate');
        Platform::doReady();
        Platform::doReadyAfter();
        WatchTime::logTime(true, 'ready');
        return $next($request);
    }
}
