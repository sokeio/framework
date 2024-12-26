<?php

namespace Sokeio\Support\MediaStorage;

use Sokeio\FormRequest;

class MediaStorageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'action' => 'required',
            'path' => 'required',
            'data' => 'nullable',
        ];
    }
}
