<?php

namespace Sokeio\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SokeioPlatform
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        // It does other things here
        $request = applyFilters(PLATFORM_MIDDLEWARE_BEFORE, $request);
        if (($request instanceof BinaryFileResponse) ||
            ($request instanceof JsonResponse) ||
            ($request instanceof RedirectResponse) ||
            ($request instanceof StreamedResponse) ||
            ($request instanceof Response)
        ) {

            return $request;
        }
        $response = $next($request);
        $response = applyFilters(PLATFORM_MIDDLEWARE_AFTER, $response, $request);
        if (isset($response->headers)) {
            $response->headers->set('X-Powered-By', 'Sokeio Framework');
        }
        return $response;
    }
}
