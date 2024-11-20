<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Sokeio\Attribute\Route;
use Sokeio\Concerns\FileManager;
use Sokeio\Enums\MethodType;
use Sokeio\Http\Requests\FileManager\ActionRequest;

class FileManagerController extends Controller
{
    use FileManager;

    #[Route(MethodType::POST, '/file-manager')]
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
}
