<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Sokeio\Attribute\RouteInfo;
use Sokeio\Enums\MethodType;

class SocialiteController extends Controller
{
    #[RouteInfo(MethodType::GET, 'login/{social}', name: 'socialite.login')]
    public function login($social)
    {
        return Socialite::driver($social)->redirect();
    }
    #[RouteInfo(MethodType::GET, 'login/{social}/callback', name: 'socialite.callback')]
    public function callback($social)
    {
        return response()->json(Socialite::driver($social)->user());
    }
}
