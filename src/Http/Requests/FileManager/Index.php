<?php

namespace Sokeio\Http\Requests\FileManager;

use Sokeio\FormRequest;

class Index extends FormRequest
{
    public function rules()
    {
        return [
            'path' => ['required'],
            'search' => ['nullable']
        ];
    }
}
