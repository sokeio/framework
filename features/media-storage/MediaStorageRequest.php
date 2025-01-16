<?php

namespace Sokeio\MediaStorage;

use Sokeio\FormRequest;

class MediaStorageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'service' => 'required',
            'action' => 'required',
            'path' => 'required',
            'data' => 'nullable',
        ];
    }
}
