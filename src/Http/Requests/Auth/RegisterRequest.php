<?php

namespace Sokeio\Http\Requests\Auth;

use Sokeio\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
        ];
    }
}
