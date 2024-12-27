<?php

namespace Sokeio\Support\MediaStorage\Local;

use Sokeio\Support\MediaStorage\MediaStorageService;
use Illuminate\Support\Facades\Storage;

class LocalService extends MediaStorageService
{
    public function getMenuContext()
    {
        return [
            [
                'name' => 'Download',
                'icon' => 'ti ti-download',
                'action' => 'download',
                'class' => '',
            ]
        ];
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
            'created_at' => $storage->lastModified($file),
            'updated_at' => $storage->lastModified($file),
            'preview_url' => Storage::temporaryUrl($file, now()->addSeconds(15), ['disk' => $disk]),
        ];
    }
    private function mapInfoFolder($folder, $storage, $path)
    {
        // check last $path char == /
        // if (substr($path, -1) != '/') {
        //     $path .= '/';
        // }

        return [
            'name' => basename($folder),
            'path' => $folder,
            'file_count' => count($storage->files($folder)),
        ];
    }
    public function getFiles($action, $path, $data)
    {
        return collect(Storage::disk('local')->files($path))->map(function ($file) {
            return $this->mapInfoFile($file, Storage::disk('local'), 'local');
        })->where('name_without_extension', '!=', '')->values()->toArray();
    }
    public function getFolders($action, $path, $data)
    {
        return collect(Storage::disk('local')->directories($path))
            ->map(function ($folder) use ($path) {
                return $this->mapInfoFolder($folder, Storage::disk('local'), $path);
            })->where('name', '!=', '')->values()->toArray();
    }
    public function getName()
    {
        return 'Local';
    }
}
