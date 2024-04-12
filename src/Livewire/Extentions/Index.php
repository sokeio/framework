<?php

namespace Sokeio\Livewire\Extentions;

use Sokeio\Breadcrumb;
use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Theme;

class Index extends Component
{
    public $ExtentionType;
    public $viewType = 'manager';
    public function switchStore()
    {
        $this->viewType = 'store';
    }
    public function switchManager()
    {
        $this->viewType = 'manager';
    }
    public function switchUpload()
    {

        $this->viewType = 'upload';
    }
    public function getTitle()
    {
        return str($this->ExtentionType)->studly();
    }
    public function render()
    {
        Assets::setTitle($this->getTitle());
        breadcrumb()->title($this->getTitle())->breadcrumb([
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ]);
        return view('sokeio::extentions.index', [
            'mode_dev' => sokeioModeDev(),
            'page_title' => $this->getTitle()
        ]);
    }
}
