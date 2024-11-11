<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Sokeio\Http\Requests\FileManager\Index;

class FileManagerController extends Controller
{
    private function getInfoByPath($path, $disk = 'public')
    {
        $storage = Storage::disk($disk);
        $files =  $storage->allFiles($path);
        $folders =  $storage->directories($path);

        return [
            'files' => $files,
            'folders' => $folders,
            'path' => $path,
            'disk' => $disk,
            'disks' => collect(config('filesystems.disks'))->map(function ($disk, $name) {
                return [
                    'name' => $name,
                    'icon' => isset($disk['icon']) ? $disk['icon'] : 'storage',
                    'title' => isset($disk['title']) ? $disk['title'] : $name,
                    // 'path' => $disk['root'],
                ];
            }),
        ];
    }
    public function index(Index $request)
    {
        return response()->json($this->getInfoByPath($request->path ?? '/', $request->disk ?? 'public'));
    }
}
