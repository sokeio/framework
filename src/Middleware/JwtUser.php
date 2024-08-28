<?php

namespace Sokeio\Middleware;


use Closure;
use Illuminate\Http\Request;
use Sokeio\Platform;

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
        $user = null;
        $token = $request->bearerToken();
        $user = Platform::gate()->getUserByToken($token);
        $request->setUserResolver((function () use ($user) {
            return $user;
        }));
        Platform::gate()->setUser($user);
        return $next($request);
    }
}
