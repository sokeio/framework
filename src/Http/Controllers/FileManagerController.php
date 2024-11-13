<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Sokeio\Http\Requests\FileManager\ActionRequest;

class FileManagerController extends Controller
{
    use FileManager;
    private $action = [
        'list' => 'listAction',
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
            'search' => $request['search']
        ];
    }
    public function index(ActionRequest $request)
    {
        $disk = $request->disk ?? 'public';
        $storage = null;
        try {
            $storage = Storage::disk($disk);
            if (array_key_exists($request['action'], $this->action)) {
                $method = $this->action[$request['action']];
                $this->$method($this->getDataInfoFromRequest($request), $storage);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        if (!$storage) {
            $disk = 'public';
            $storage = Storage::disk($disk);
        }

        return response()->json($this->getInfoByPath($request->path ?? '/', $storage, $disk));
    }
    private function listAction($data)
    {
        // Code to list files
        // Implement file listing logic here
    }
    private function createFolderAction($data, $storage)
    {
        // Code to create folders
        // Implement folder creation logic here

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
    private function uploadAction($data)
    {
        // Code to upload files
        // Implement file upload logic here
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
