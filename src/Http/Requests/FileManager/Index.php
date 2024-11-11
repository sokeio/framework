<?php

namespace Sokeio\Http\Requests\FileManager;

use Sokeio\FormRequest;

class Index extends FormRequest
{
    public function rules()
    {
        return [
            'path' => ['nullable', 'string'],
            'disk' => ['nullable'],
            'search' => ['nullable']
        ];
    }
}
