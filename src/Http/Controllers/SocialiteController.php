<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Sokeio\Core\Attribute\RouteInfo;
use Sokeio\Enums\MethodType;

class SocialiteController extends Controller
{
    #[RouteInfo(
        MethodType::GET,
        'login/{social}',
        name: 'socialite.login',
        where: [['social', '.*']],
        isWeb: true,
        isAuth: false
    )]
    public function login($social)
    {
        try {
            $driver = Socialite::driver($social);
            if (!$driver) {
                abort(404);
            }
            return $driver->redirect();
        } catch (\Exception $e) {
            abort(404);
        }
    }
    #[RouteInfo(
        MethodType::GET,
        'login/{social}/callback',
        name: 'socialite.callback',
        where: [['social', '.*']],
        isWeb: true,
        isAuth: false
    )]
    public function callback($social)
    {
        return response()->json(Socialite::driver($social)->user());
    }
}
