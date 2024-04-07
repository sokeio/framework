<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileManangerController extends BaseController
{
    private function getStorage($local = 'local')
    {
        if ($local == '') {
            $local = 'local';
        }
        return Storage::disk($local);
    }
    private function getFolderInfo($local, $path)
    {
        return [
            'path' => $path,
            'name' => basename($path),
            // 'size' => $this->getStorage($local)->size($path),
            'type' => 'folder',
            'file_count' => count($this->getStorage($local)->files($path)),
        ];
    }
    private function getFileInfo($local, $path)
    {
        return [
            'path' => $path,
            'name' => basename($path),
            'ext' => pathinfo($path, PATHINFO_EXTENSION),
            'size' => $this->getStorage($local)->size($path),
            'type' => 'file',
            'modified_at' => $this->getStorage($local)->lastModified($path)
        ];
    }
    public function getDiskAll($local = 'local', $directory = '')
    {
        $disk = $this->getStorage($local);
        return [
            'disk' => $local,
            'disks' => ['local', 'public'],
            'path' => $directory,
            'name' => basename($directory),
            'folders' => collect($disk->directories($directory))->map(function ($path) use ($local) {
                return $this->getFolderInfo($local, $path);
            }),
            'files' => collect($disk->files($directory))->map(function ($path) use ($local) {
                return $this->getFileInfo($local, $path);
            })->filter(function ($file) {
                return $file['size'] > 0 && $file['ext'] != 'php' && '.' . $file['ext'] != $file['name'];
            })
        ];
    }
    public function getIndex()
    {
        ['action' => $action, 'data' => $data] = request()->all();
        if ($action === 'createFolder') {
            $disk = $this->getStorage($data['disk'] ?? 'local');
            if ($disk->exists($data['path'] . $data['name'])) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'Folder already exists'
                ]);
            }
            $disk->makeDirectory($data['path'] . '/' . $data['name']);
        }
        return $this->getDiskAll($data['disk'] ?? 'local', $data['path'] ?? '');
    }
}
