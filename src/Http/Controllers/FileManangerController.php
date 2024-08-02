<?php

namespace Sokeio\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
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
        $name = basename($path);
        return [
            'path' => $path,
            'directory' => $name === $path ? '/' : dirname($path),
            'name' => $name,
            'name_without_ext' => pathinfo($path, PATHINFO_FILENAME),
            'ext' => pathinfo($path, PATHINFO_EXTENSION),
            'mime_type' => $storage->mimeType($path),
            'size' => $this->convertFileSize($storage->size($path)),
            'type' => 'file',
            'url' => url('storage/' . $path),
            'thumb' => url('storage/' . $path),
            'modified_at' => Carbon::parse($storage->lastModified($path))->format('Y-m-d H:i:s')
        ];
    }
    private function getDiskAll($disk = 'local', $directory = '')
    {
        $storage = $this->getStorage($disk);
        return [
            'disk' => $disk,
            'disks' => ['local', 'public'],
            'path' => $directory,
            'name' => basename($directory),
            'folders' => collect($storage->directories($directory))
                ->sortBy('name', SORT_STRING)
                ->map(function ($path) use ($disk) {
                    return $this->getFolderInfo($disk, $path);
                }),
            'files' => [...collect($storage->files($directory))
                ->sortBy('name', SORT_STRING)
                ->map(function ($path) use ($disk) {
                    return $this->getFileInfo($disk, $path);
                })->filter(function ($file) {
                    return $file['size'] > 0 && $file['ext'] !== 'php' && !str($file['name'])->startsWith('.');
                })]
        ];
    }
    public function postIndex()
    {
        if (!auth()->check()) {
            return Response::json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }
        ['action' => $action, 'data' => $data] = request()->all();
        $item = $data['item'] ?? [];
        $name = $data['name'] ?? '';
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
        if ($action === 'delete' && $data['disk'] && isset($item['type']) && isset($item['path'])) {
            if ($item['type'] === 'folder') {
                $disk = $this->getStorage($data['disk']);
                $disk->deleteDirectory($item['path']);
            } elseif ($item['type'] === 'file'  && $data['path'] === $item['directory']) {
                $disk = $this->getStorage($data['disk']);
                $disk->delete($item['path']);
            }
        }
        if ($action === 'rename' && $data['disk'] && isset($item['type']) && isset($item['path']) && $name) {
            $disk = $this->getStorage($data['disk']);
            if ($disk->exists(dirname($item['path']) . '/' . $name)) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'Name already exists'
                ]);
            }
            $disk->move($item['path'], dirname($item['path']) . '/' . $name);
        }
        return $this->getDiskAll($data['disk'] ?? 'public', $data['path'] ?? '');
    }
    public function postUpload()
    {
        if (!auth()->check()) {
            return Response::json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }
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
        foreach ($requestedFiles as $requestedFileValue) {
            $name = $requestedFileValue->getClientOriginalName();
            Storage::disk($disk)->put($path . '/' . $name, file_get_contents($requestedFileValue));
        }
        return $this->getDiskAll($disk, $path ?? '');
    }
}
