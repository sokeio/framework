<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Storage;
use Sokeio\Component;

class MediaFile extends Component
{
    public $local = 'public';
    public $directory = '/';
    private function getStorage()
    {
        return Storage::disk($this->local);
    }
    public function getDiskAll()
    {
        $this->skipRender();
        $disk = $this->getStorage();
        return [
            'directories' => $disk->directories($this->directory),
            'files' => $disk->files($this->directory)
        ];
    }

    public function render()
    {
        return view('sokeio::media-file.index');
    }
}
