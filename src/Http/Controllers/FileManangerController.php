<?php

namespace Sokeio\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileManangerController extends BaseController
{
    private function getStorage($disk = 'local')
    {
        if ($disk == '') {
            $disk = 'local';
        }
        return Storage::disk($disk);
    }
    private function getFolderInfo($disk, $path)
    {
        return [
            'path' => $path,
            'name' => basename($path),
            'type' => 'folder',
            'file_count' => count($this->getStorage($disk)->files($path)),
            'modified_at' => Carbon::parse($this->getStorage($disk)->lastModified($path))->format('Y-m-d H:i:s')
        ];
    }
    private function convertFileSize($bytes)
    {
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        $i = 0;
        while ($bytes > 1024) {
            $bytes /=  1024;
            $i++;
            if ($i > 5) {
                break;
            }
        }

        return round($bytes, 2) . ' ' . $unit[$i];
    }
    private function getFileInfo($disk, $path)
    {
        $storage = $this->getStorage($disk);
        return [
            'path' => $path,
            'name' => basename($path),
            'ext' => pathinfo($path, PATHINFO_EXTENSION),
            'mime_type' => $storage->mimeType($path),
            'size' => $this->convertFileSize($storage->size($path)),
            'type' => 'file',
            'url' => url('storage/' . $path),
            'thumb' => url('storage/' . $path),
            'modified_at' => Carbon::parse($storage->lastModified($path))->format('Y-m-d H:i:s')
        ];
    }
    public function getDiskAll($disk = 'local', $directory = '')
    {
        $storage = $this->getStorage($disk);
        return [
            'disk' => $disk,
            'disks' => ['local', 'public'],
            'path' => $directory,
            'name' => basename($directory),
            'folders' => collect($storage->directories($directory))->map(function ($path) use ($disk) {
                return $this->getFolderInfo($disk, $path);
            }),
            'files' => [...collect($storage->files($directory))->map(function ($path) use ($disk) {
                return $this->getFileInfo($disk, $path);
            })->filter(function ($file) {
                return $file['size'] > 0 && $file['ext'] !== 'php' && !str($file['name'])->startsWith('.');
            })]
        ];
    }
    public function postIndex()
    {
        ['action' => $action, 'data' => $data] = request()->all();
        if ($action === 'createFolder') {
            $disk = $this->getStorage($data['disk'] ?? 'public');
            if ($disk->exists($data['path'] . $data['name'])) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'Folder already exists'
                ]);
            }
            $disk->makeDirectory($data['path'] . '/' . $data['name']);
        }
        return $this->getDiskAll($data['disk'] ?? 'public', $data['path'] ?? '');
    }
    public function postUpload()
    {
        ['disk' => $disk, 'path' => $path] = request()->all();
        $requestedFiles = request('files');
        if ($disk == '') {
            $disk = 'local';
        }
        if ($path == '') {
            $path = '/';
        }
        if (!is_array($requestedFiles)) {
            $requestedFiles = [$requestedFiles];
        }
        foreach ($requestedFiles as $requestedFileKey => $requestedFileValue) {
            $name = $requestedFileValue->getClientOriginalName();
            Storage::disk($disk)->put($path . '/' . $name, file_get_contents($requestedFileValue));
        }
        return $this->getDiskAll($disk, $path ?? '');
    }
}
