<?php

namespace Sokeio\Components;

use Illuminate\Support\Facades\File;
use Illuminate\View\Component;
use Illuminate\View\View;

class SokeioTemplate extends Component
{
    public function __construct(
        public $view = 'sokeio::components.sokeio-template',
        public $data = [],
        public $content = '',
        public $file = null
    ) {
        if ($file && File::exists($file)) {
            $this->content = File::get($file);
        }
    }

    public function render(): View
    {
        if (!view()->exists($this->view)) {
            return view('sokeio::components.sokeio-template');
        }
        return view($this->view);
    }
}
