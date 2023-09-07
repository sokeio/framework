<?php

namespace BytePlatform\Middleware;

use BytePlatform\Facades\Locale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Platform
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        Locale::SetLocaleApp();
        // It does other things here
        $request = apply_filters(PLATFORM_MIDDLEWARE_BEFORE, $request);
        if (($request instanceof BinaryFileResponse) or
            ($request instanceof JsonResponse) or
            ($request instanceof RedirectResponse) or
            ($request instanceof StreamedResponse) or
            ($request instanceof Response)
        )
            return $request;
        $response = $next($request);
        $response = apply_filters(PLATFORM_MIDDLEWARE_AFTER, $response, $request);
        return $response;
    }
}
