<?php

namespace Sokeio\Support\MediaStorage;

use Sokeio\MediaStorage;

class MediaStorageController extends \Illuminate\Routing\Controller
{
    public function action(MediaStorageRequest $request)
    {
        $service = $request->get('service');
        $path = $request->get('path');
        $data = $request->get('data');
        $action = $request->get('action');
        $files = $request->allFiles();
        if (isset($files['data']['files'])) {
            $files = $files['data']['files'];
        }
        if ($data == null) {
            $data = [];
        }
        $data['files'] = $files;
        return MediaStorage::action($service, $action, $path, $data);
    }
}
