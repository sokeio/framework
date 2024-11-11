<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Sokeio\Http\Requests\FileManager\Index;

class FileManagerController extends Controller
{
    public function index(Index $request)
    {
        return response()->json([
            'path' => $request->path
        ]);
    }
}
