<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Sokeio\Http\Requests\FileManager\ActionRequest;

class FileManagerController extends Controller
{
    use FileManager;
    //list,upload,delete,rename,download,move
    private $action = [
        'list' => 'listAction',
        'upload' => 'uploadAction',
        'delete' => 'deleteAction',
        'rename' => 'renameAction',
        'download' => 'downloadAction',
        'move' => 'moveAction',
    ];
    public function index(ActionRequest $request)
    {
        if (array_key_exists($request['action'], $this->action)) {
            $method = $this->action[$request['action']];
            $this->$method($request);
        }
        return response()->json($this->getInfoByPath($request->path ?? '/', $request->disk ?? 'public'));
    }
    private function listAction($request)
    {
        // Code to list files
        // Implement file listing logic here
    }

    private function uploadAction($request)
    {
        // Code to upload files
        // Implement file upload logic here
    }

    private function deleteAction($request)
    {
        // Code to delete files
        // Implement file deletion logic here
    }

    private function renameAction($request)
    {
        // Code to rename files
        // Implement file renaming logic here
    }

    private function downloadAction($request)
    {
        // Code to download files
        // Implement file download logic here
    }

    private function moveAction($request)
    {
        // Code to move files
        // Implement file moving logic here
    }
}
