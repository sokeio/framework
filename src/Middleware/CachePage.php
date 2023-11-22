<?php

namespace Sokeio\Middleware;


use Closure;
use Sokeio\Facades\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CachePage
{
    const KEY_CACHE_PAGE = 'KEY_CACHE_PAGE';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Platform::enableCachePage();
        if (Platform::checkCachePage() && !byte_is_admin() && $request->isMethod('get')) {
            $key = md5($request->url());
            if ($repos = Cache::get(self::KEY_CACHE_PAGE . $key)) {
                return $repos;
            }
        }
        /*
        * @var \Illuminate\Http\Response $response
        */
        $response = $next($request);
        if (Platform::checkCachePage() && !byte_is_admin() && $request->isMethod('get')) {
            if (in_array($response->status(), [200, 201, 400]) && trim($response->content()) != '') {
                $key = md5($request->url());
                Cache::put(self::KEY_CACHE_PAGE . $key, $response);
            }
            Platform::disableCachePage();
        }
        return  $response;
    }
}
