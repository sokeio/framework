<?php

namespace Sokeio\Support\MediaStorage\Local;

use Sokeio\Support\MediaStorage\MediaStorageService;
use Illuminate\Support\Facades\Storage;

class LocalService extends MediaStorageService
{
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
    private function mapInfoFolder($folder, $storage, $disk)
    {
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
            ->map(function ($folder) {
                return $this->mapInfoFolder($folder, Storage::disk('local'), 'local');
            })->where('name', '!=', '')->values()->toArray();
    }
    public function getName()
    {
        return 'Local';
    }
}
