<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Facades\Shortcode as FacadesShortcode;
use BytePlatform\Forms\WithFormData;
use Livewire\Attributes\Reactive;

class Shortcode extends Component
{
    #[Reactive]
    public $shortcode;
    #[Reactive]
    public $attrs = [];
    #[Reactive]
    public $content;
    use WithFormData;
    protected function getListeners()
    {
        return [
            ...parent::getListeners(),
            'refreshData' . $this->shortcode => '__loadData',
        ];
    }
    protected function ItemManager()
    {
        if (!$this->shortcode) {
            return;
        }
        return FacadesShortcode::getShortCodeByKey($this->shortcode);
    }
    public function render()
    {
        return view('byte::shortcode', [
            'shortcode' => $this->shortcode,
            'attrs' => $this->attrs,
            'content' => $this->content,
            'manager' => $this->getItemManager()?->ClearCache()->Data(['attrs' => $this->attrs, 'content' => $this->content, 'component' => $this])->beforeRender($this)
        ]);
    }
}
