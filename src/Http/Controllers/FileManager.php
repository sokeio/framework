<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Support\Facades\Storage;

trait FileManager
{


    private function getFolders($arrPath, $storage, $path, $name, $level, $pathCurrent)
    {
        $folders = [
            'name' => $name,
            'path' => $path,
            'level' => $level,
            'children' => [],
        ];
        if ((str($pathCurrent)->startsWith($path)) || $level == 0) {
            $folders['children'] = array_map(function ($item) use ($arrPath, $storage, $level, $pathCurrent) {
                return $this->getFolders($arrPath, $storage, $item, basename($item), $level + 1, $pathCurrent);
            }, $storage->directories($path));
        }

        return $this->mapInfoFolder($folders);
    }
    private function getIcon($disk)
    {
        if (isset($disk['icon']) && $disk['icon']) {
            return $disk['icon'];
        }
        return '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-onedrive"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18.456 10.45a6.45 6.45 0 0 0 -12 -2.151a4.857 4.857 0 0 0 -4.44 5.241a4.856 4.856 0 0 0 5.236 4.444h10.751a3.771 3.771 0 0 0 3.99 -3.54a3.772 3.772 0 0 0 -3.538 -3.992z" /></svg>';
    }
    private function getInfoByPath($path, $disk = 'public')
    {
        $storage = Storage::disk($disk);
        $path = str($path)->trim('/');
        $arrPath = explode('/', $path);
        $folders = $this->getFolders($arrPath, $storage, '/', 'Root', 0, $path);
        return [
            'files' => collect($storage->files($path))
                ->map(fn($file) => $this->mapInfoFile($file, $storage))
                ->toArray(),
            'folders' => $folders,
            'path' => $path,
            'disk' => $disk,
            'disks' => collect(config('filesystems.disks'))->map(function ($disk, $name) {
                return [
                    'name' => $name,
                    'icon' => $this->getIcon($disk),
                    'title' => isset($disk['title']) ? $disk['title'] : $name,
                ];
            }),
        ];
    }
    private function mapInfoFile($file, $storage)
    {
        return [
            'name' => basename($file),
            'path' => $file,
            'extension' => $storage->mimeType($file),
        ];
    }
    private function mapInfoFolder($folder)
    {
        return $folder;
    }
}
