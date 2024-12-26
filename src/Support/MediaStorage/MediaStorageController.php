<?php

namespace Sokeio\Support\MediaStorage;

use Sokeio\MediaStorage;

class MediaStorageController extends \Illuminate\Routing\Controller
{
    public function action(MediaStorageRequest $request)
    {
        $type = $request->get('type');
        $path = $request->get('path');
        $data = $request->get('data');
        $action = $request->get('action');
        return MediaStorage::action($type, $action, $path, $data);
    }
}
