<?php

namespace Sokeio\Support\MediaStorage\StorageDisk;

use Sokeio\Support\MediaStorage\MediaStorageService;
use Illuminate\Support\Facades\Storage;

class StorageDiskService extends MediaStorageService
{
    private $disk = 'public';
    private $name = "local";
    private $key = null;
    private $tempStorage = null;
    private $fnWhen = null;
    public static function make($disk = 'public', $key, $name = "local", callable $when = null)
    {
        return (new self)->tap(function ($instance) use ($disk, $key, $name, $when) {
            $instance->disk = $disk;
            $instance->name = $name;
            $instance->fnWhen = $when;
            $instance->key = $key;
        });
    }
    public function when()
    {
        if ($this->fnWhen) {
            return call_user_func($this->fnWhen, $this);
        }
        return true;
    }
    public function getMediaStoreage(): mixed
    {
        if (!$this->tempStorage) {
            $this->tempStorage = Storage::disk($this->disk);
        }
        return $this->tempStorage;
    }
    public function getViews()
    {
        return [
            'local::create' => file_get_contents(__DIR__ . '/views/create.js'),
            'local::rename-folder' => file_get_contents(__DIR__ . '/views/rename-folder.js'),
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
                'name' => 'Copy Link',
                'icon' => 'ti ti-copy',
                'action' => 'navigator.clipboard.writeText(file.public_url);alert("Copied")',
                'class' => '',
                'type' => ['file:public']
            ],
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
                'view' => 'local::rename-folder',
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
        // check $file is Image
        $isImage = false;
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $isImage = true;
        }
        $public_url = "";
        if (config('filesystems.disks.' . $disk . '.url')) {
            $public_url = config('filesystems.disks.' . $disk . '.url') . '/' . $file;
        }
        return [
            'name' => basename($file),
            'name_without_extension' => pathinfo($file, PATHINFO_FILENAME),
            'path' => $file,
            'extension' => $extension,
            'size' => $storage->size($file),
            'public_url' =>  $public_url,
            'created_at' => $storage->lastModified($file),
            'updated_at' => $storage->lastModified($file),
            'preview_url' => $isImage ? Storage::temporaryUrl($file, now()->addSeconds(15), ['disk' => $disk]) : null,
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
        return collect($this->getMediaStoreage()->files($path))->map(function ($file) {
            return $this->mapInfoFile($file, $this->getMediaStoreage(), $this->disk);
        })->where('name_without_extension', '!=', '')->values()->toArray();
    }
    public function getFolders($action, $path, $data)
    {
        return collect($this->getMediaStoreage()->directories($path))
            ->map(function ($folder) use ($path) {
                return $this->mapInfoFolder($folder, $this->getMediaStoreage(), $path);
            })->where('name', '!=', '')->values()->toArray();
    }
    public function getName()
    {
        return $this->name;
    }
    public function getKey()
    {
        return $this->key;
    }

    // *** Action *** //
    public function createFolderAction($path, $data)
    {
        if (!isset($data['name'])) {
            return;
        }
        $this->getMediaStoreage()->makeDirectory($path . '/' . $data['name']);
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
            $this->getMediaStoreage()->putFileAs($path, $file, $name);
        }
    }
    public function deleteFolderAction($path, $data)
    {
        if (!isset($data['path'])) {
            return;
        }
        $pathFolder =  $data['path'];
        $this->getMediaStoreage()->deleteDirectory($pathFolder);
    }
    public function deleteFileAction($path, $data)
    {
        if (!isset($data['path'])) {
            return;
        }
        $pathFile =  $data['path'];
        $this->getMediaStoreage()->delete($pathFile);
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
        $this->getMediaStoreage()->move($pathFile, $pathNew);
    }
    public function renameFolderAction($path, $data)
    {
        if (!isset($data['path']) || !isset($data['name'])) {
            return;
        }
        $pathFolder =  $data['path'];
        $nameFolder =  $data['name'];

        $pathNew = dirname($pathFolder);
        if ($pathNew == '.') {
            $pathFolder = './' . $pathFolder;
        }
        if ($pathNew) {
            $pathNew = $pathNew . '/' . $nameFolder;
        } else {
            $pathNew = $nameFolder;
        }
        $this->getMediaStoreage()->move($pathFolder, $pathNew);
    }
}
