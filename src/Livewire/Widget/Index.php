<?php

namespace Sokeio\Livewire\Widget;

use Livewire\Attributes\Reactive;
use Sokeio\Facades\Dashboard;
use Sokeio\Component;
use Sokeio\Components\Concerns\WithActionUI;

class Index extends Component
{
    use WithActionUI;
    #[Reactive]
    public $widgetId;

    #[Reactive]
    public $locked = false;
    private $widgetItem;
    public function booted()
    {
        parent::booted();
        $this->widgetItem = Dashboard::getWidgetByKey($this->widgetId)?->component($this);
    }
    public function render()
    {
        return view('sokeio::widget.index', [
            'widget' => $this->widgetItem,
        ]);
    }
}
