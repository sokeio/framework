<?php

namespace Sokeio\Middleware;

use Sokeio\Facades\Locale;
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
        Locale::setLocaleApp();
        // It does other things here
        $request = apply_filters(PLATFORM_MIDDLEWARE_BEFORE, $request);
        if (($request instanceof BinaryFileResponse) ||
            ($request instanceof JsonResponse) ||
            ($request instanceof RedirectResponse) ||
            ($request instanceof StreamedResponse) ||
            ($request instanceof Response)
        ) {

            return $request;
        }
        $response = $next($request);
        $response = apply_filters(PLATFORM_MIDDLEWARE_AFTER, $response, $request);
        return $response;
    }
}
