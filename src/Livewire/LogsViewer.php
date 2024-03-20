<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Support\LogView\LogViewManager;

class LogsViewer extends Component
{
    public $files = [];
    public $filePath = '';
    public $colorTypes = [
        'info' => 'bg-teal text-teal-fg',
        'error' => 'bg-red text-red-fg',
        'warning' => 'bg-yellow text-yellow-fg',
        'debug' => 'bg-muted text-muted-fg',
    ];
    public function setFilePath($path)
    {
        $this->filePath = $path;
        return $this->loadLogs();
    }
    public function mount()
    {
        $this->files = LogViewManager::inst()->files;
        Assets::setTitle(__('Logs Viewer'));
    }
    public function loadLogs()
    {
        $this->files = LogViewManager::inst()->files;
        LogViewManager::inst()->filePath = $this->filePath;
        $rs =   LogViewManager::inst()->readLogs();
        $this->filePath = LogViewManager::inst()->filePath;
        return $rs;
    }
    public function render()
    {
        return view('sokeio::logs-viewer');
    }
}
