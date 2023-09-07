<?php

namespace BytePlatform\Middleware;


use Closure;
use Illuminate\Http\Request;
use BytePlatform\Facades\Jwt;

class JwtUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->setUserResolver((function () use ($request) {
            $token = $request->bearerToken();
            if (Jwt::verify($token))
                return Jwt::decode($token);
            return null;
        }));
        return $next($request);
    }
}
