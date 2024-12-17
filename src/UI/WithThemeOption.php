<?php

namespace Sokeio\UI;

use Sokeio\Theme;

trait WithThemeOption
{
    use WithUI;
    public const COLUMN_GROUP = 'col-sm-12 col-md-12 col-lg-12';
    public const COLUMN_GROUP2 = 'col-sm-12 col-md-6 col-lg-6';
    public $formData = [];
    public function saveData()
    {
        Theme::setOptions($this->formData);
        $this->alert('Setting has been saved!', 'Setting', 'success');
    }
    public function mount()
    {
        $this->formData = Theme::option()??[];
    }
}
