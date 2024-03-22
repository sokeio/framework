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
    public function createFolder($name)
    {
        $this->skipRender();
        $this->getStorage()->makeDirectory($this->directory . $name);
        return $this->getDiskAll();
    }
    public function selectFolder($name)
    {
        $this->skipRender();
        $this->directory = $this->directory . $name . '/';
        return $this->getDiskAll();
    }
    public function getDiskAll()
    {
        $this->skipRender();
        $disk = $this->getStorage();
        return [
            'folders' => $disk->directories($this->directory),
            'files' => $disk->files($this->directory)
        ];
    }
    public function deleteFolder($name){
        $this->skipRender();
        $this->getStorage()->deleteDirectory($this->directory . $name);
        return $this->getDiskAll();
    }
    public function deleteFile($name){
        $this->skipRender();
        $this->getStorage()->delete($this->directory . $name);
        return $this->getDiskAll();
    }

    public function render()
    {
        return view('sokeio::media-file.index');
    }
}
