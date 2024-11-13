<?php

namespace Sokeio\Http\Requests\FileManager;

use Sokeio\FormRequest;

class ActionRequest extends FormRequest
{

    public function rules()
    {
    //    dd($this->all());
        return [
            'action' => ['required', 'string', 'in:list,create-folder,upload,delete,rename,download,move'],
            'payload' => ['nullable', 'array'],
            'path' => ['nullable', 'string'],
            'disk' => ['nullable'],
            'search' => ['nullable']
        ];
    }
}
