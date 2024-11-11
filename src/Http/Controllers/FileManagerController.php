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
        // $treeFolders =str($path)->split('/')->map(function ($folder) {
            
        // });
        return [
            'files' => $this->mapInfoFile($files),
            'folders' => $this->mapInfoFolder($folders),
            'path' => $path,
            'disk' => $disk,
            'disks' => collect(config('filesystems.disks'))->map(function ($disk, $name) {
                return [
                    'name' => $name,
                    'icon' => isset($disk['icon']) ? $disk['icon'] : '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-onedrive"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18.456 10.45a6.45 6.45 0 0 0 -12 -2.151a4.857 4.857 0 0 0 -4.44 5.241a4.856 4.856 0 0 0 5.236 4.444h10.751a3.771 3.771 0 0 0 3.99 -3.54a3.772 3.772 0 0 0 -3.538 -3.992z" /></svg>',
                    'title' => isset($disk['title']) ? $disk['title'] : $name,
                    // 'path' => $disk['root'],
                ];
            }),
        ];
    }
    private function mapInfoFile($file)
    {
        return $file;
    }
    private function mapInfoFolder($folder)
    {
        return $folder;
    }
    public function index(Index $request)
    {
        return response()->json($this->getInfoByPath($request->path ?? '/', $request->disk ?? 'public'));
    }
}
