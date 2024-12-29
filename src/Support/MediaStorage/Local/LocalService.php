<?php

namespace Sokeio\Support\MediaStorage\Local;

use Sokeio\Support\MediaStorage\MediaStorageService;
use Illuminate\Support\Facades\Storage;

class LocalService extends MediaStorageService
{
    public function getViews()
    {
        return [
            'local::create' => file_get_contents(__DIR__ . '/views/create.js'),
            'local::rename-file' => file_get_contents(__DIR__ . '/views/rename-file.js'),
            'local::upload' => file_get_contents(__DIR__ . '/views/upload.js'),
            'local::delete-folder' => file_get_contents(__DIR__ . '/views/delete-folder.js'),
            'local::delete-file' => file_get_contents(__DIR__ . '/views/delete-file.js'),
        ];
    }
    public function getToolbar()
    {
        return [
            [
                'name' => 'Create Folder',
                'icon' => 'ti ti-plus',
                'class' => '',
                'view' => 'local::create',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
            ],
            [
                'name' => 'Upload File',
                'icon' => 'ti ti-upload',
                'class' => '',
                'view' => 'local::upload',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
            ]
        ];
    }
    public function getFooter()
    {
        return [];
    }
    public function getMenuContext()
    {
        return [
            [
                'name' => 'Remove Folder',
                'icon' => 'ti ti-trash',
                'view' => 'local::delete-folder',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
                'title' => 'Remove Folder',
                'class' => '',
                'type' => ['folder']
            ],
            [
                'name' => 'Rename Folder',
                'icon' => 'ti ti-pencil',
                'view' => 'local::demo',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
                'title' => 'Rename Folder',
                'class' => '',
                'type' => ['folder']
            ],
            [
                'name' => 'Remove File',
                'icon' => 'ti ti-trash',
                'view' => 'local::delete-file',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
                'title' => 'Remove File',
                'class' => '',
                'type' => ['file']
            ],
            [
                'name' => 'Download',
                'icon' => 'ti ti-download',
                'action' => 'console.log(this);',
                'class' => '',
                'type' => ['file']
            ],
            [
                'name' => 'Rename File',
                'icon' => 'ti ti-pencil',
                'view' => 'local::rename-file',
                'viewOptions' => [
                    'modalSize' => 'md',
                ],
                'title' => 'Rename File',
                'class' => '',
                'type' => ['file']
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

    // *** Action *** //
    public function createFolderAction($path, $data)
    {
        if (!isset($data['name'])) {
            return;
        }
        Storage::disk('local')->makeDirectory($path . '/' . $data['name']);
    }
    public function uploadFileAction($path, $data)
    {
        if (!isset($data['files'])) {
            return;
        }
        foreach ($data['files'] as $file) {
            // add time to name file eg:abc.jpge => abc-time.jpge
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $filename . time() . '.' . $extension;
            Storage::disk('local')->putFileAs($path, $file, $name);
        }
    }
    public function deleteFolderAction($path, $data)
    {
        if (!isset($data['path'])) {
            return;
        }
        $pathFolder =  $data['path'];
        Storage::disk('local')->deleteDirectory($pathFolder);
    }
    public function deleteFileAction($path, $data)
    {
        if (!isset($data['path'])) {
            return;
        }
        $pathFile =  $data['path'];
        Storage::disk('local')->delete($pathFile);
    }
    public function renameFileAction($path, $data)
    {

        if (!isset($data['path']) || !isset($data['name'])) {
            return;
        }
        $pathFile =  $data['path'];
        $nameFile =  $data['name'];

        $pathNew = dirname($pathFile);
        if ($pathNew == '.') {
            $pathFile = './' . $pathFile;
        }
        if ($pathNew) {
            $pathNew = $pathNew . '/' . $nameFile;
        } else {
            $pathNew = $nameFile;
        }
        Storage::disk('local')->move($pathFile, $pathNew);
    }
}
