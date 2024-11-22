<?php

namespace Sokeio\Concerns;

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
    private function getDiskList()
    {
        return collect(config('filesystems.disks'))
            ->filter(fn($disk) => ($disk['driver'] == 'local') || ($disk['driver'] !== 's3' && $disk['key']))
            ->map(function ($disk, $name) {
                return [
                    'name' => $name,
                    'icon' => $this->getIcon($disk),
                    'title' => isset($disk['title']) ? $disk['title'] : $name,

                ];
            });
    }
    private function getInfoByPath($path, $storage, $disk)
    {
        try {
            $path = str($path)->trim('/');
            $arrPath = explode('/', $path);
            $folders = $this->getFolders($arrPath, $storage, '/', 'Home', 0, $path);
            return [
                'files' => collect($storage->files($path))
                    ->map(fn($file) => $this->mapInfoFile($file, $storage, $disk))
                    ->where('name_without_extension', '!=', '')
                    ->values()->toArray(),
                'folders' => $folders,
                'path' => $path,
                'disk' => $disk,
                'disks' => $this->getDiskList(),
            ];
        } catch (\Throwable $th) {
            return [
                'files' => [],
                'folders' => [],
                'path' => $path,
                'disk' => $disk,
                'disks' => $this->getDiskList(),
            ];
        }
    }
    private function mapInfoFile($file, $storage, $disk)
    {
        return [
            'name' => basename($file),
            'name_without_extension' => pathinfo($file, PATHINFO_FILENAME),
            'path' => $file,
            'extension' => $storage->mimeType($file),
            'size' => $storage->size($file),
            'public_url' => url('storage/' . $file),
            'preview_url' => Storage::temporaryUrl($file, now()->addSeconds(15), ['disk' => $disk]),
        ];
    }
    private function mapInfoFolder($folder)
    {
        return $folder;
    }
    private $action = [
        'create-folder' => 'createFolderAction',
        'upload' => 'uploadAction',
        'delete' => 'deleteAction',
        'rename' => 'renameAction',
        'download' => 'downloadAction',
        'move' => 'moveAction',
    ];
    private function getDataInfoFromRequest($request)
    {
        return [
            'action' => $request['action'],
            'payload' => $request['payload'],
            'path' => $request['path'],
            'disk' => $request['disk'],
            'search' => $request['search'],
            'request' => $request
        ];
    }

    private function createFolderAction($data, $storage)
    {
        $name = data_get($data, 'payload.name');
        $path = data_get($data, 'path');
        if ($path == '/') {
            $path = '';
        }
        $folderName = $path . '/' . $name;
        if ($storage->exists($folderName)) {
            return;
        }
        $folderName = trim($folderName, '/');
        $storage->makeDirectory('/' . $folderName);
    }
    private function uploadAction($data, $storage)
    {
        $path = data_get($data, 'path');
        if ($path == '/') {
            $path = '';
        }
        $request = data_get($data, 'request');
        if (!$request->hasFile('files')) {
            return;
        }
        $files = $request->file('files');
        foreach ($files as $file) {
            $storage->putFileAs($path, $file, $file->getClientOriginalName());
        }
    }

    private function deleteAction($data)
    {
        // Code to delete files
        // Implement file deletion logic here
    }

    private function renameAction($data)
    {
        // Code to rename files
        // Implement file renaming logic here
    }

    private function downloadAction($data)
    {
        // Code to download files
        // Implement file download logic here
    }

    private function moveAction($data)
    {
        // Code to move files
        // Implement file moving logic here
    }
}
