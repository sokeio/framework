<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Facades\Assets;
use BytePlatform\Facades\Theme;

class Setup extends Component
{
    public $lang;
    public $step_index = 0;
    public $step_max = 3;
    public function stepNext()
    {
        if ($this->step_index >= $this->step_max) return;
        $this->step_index++;
    }
    public function stepBack()
    {
        if ($this->step_index <= 0) return;
        $this->step_index--;
    }
    public function mount()
    {
        Theme::setTitle('System Setup');
        Assets::Theme('tabler');
        Assets::AddCss('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
    }
    public function render()
    {
        return view('byte::setup');
    }
}
