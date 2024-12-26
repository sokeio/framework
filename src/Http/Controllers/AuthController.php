<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Sokeio\Attribute\RouteInfo;
use Sokeio\Enums\MethodType;
use Sokeio\Http\Requests\Auth\LoginRequest;
use Sokeio\Http\Requests\Auth\RegisterRequest;
use Sokeio\Platform;

class AuthController extends Controller
{
    #[RouteInfo(
        MethodType::POST,
        'auth/login',
        isAuth: false
    )]
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            return Platform::apiError('Invalid account or password');
        }
        return Platform::apiOk([
            'access_token' => Auth::user()->createToken('token')
        ]);
    }
    #[RouteInfo(
        MethodType::GET,
        'auth/me',
    )]
    public function me() {}
    #[RouteInfo(
        MethodType::POST,
        'auth/register',
    )]
    public function register(RegisterRequest $request) {
        $email=$request->email;
        $password=$request->password;
        $name=$request->name;
        $phone=$request->phone;
        
        // return [
        //     'password' => ['required', 'string', 'min:8'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'name' => ['required', 'string', 'max:255'],
        //     'phone' => ['required', 'string', 'max:255'],
        // ];
    }
}
