<?php

namespace Sokeio\Http\Requests\Auth;

use Sokeio\FormRequest;

class LoginRequest extends FormRequest
{

    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:1'],
        ];
    }
}
