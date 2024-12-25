<?php

namespace Sokeio\Livewire\Platform;

use Sokeio\Component;

class Item extends Component
{
    public $itemId;
    public $itemType;
    public $lastVersion;
    public $routeName;
    private $itemInfo;
    private function getItemInfo()
    {
        if (!$this->itemInfo) {

            if ($this->itemType == 'theme') {
                $this->itemInfo = platform()->theme()->find($this->itemId);
            } else {
                $this->itemInfo = platform()->module()->find($this->itemId);
            }
        }
        return $this->itemInfo;
    }
    public function loadLastVersion()
    {
        $this->lastVersion = '1.0.0';
    }
    public function render()
    {
        return view('sokeio::livewire.platform.item', [
            'item' => $this->getItemInfo()
        ]);
    }
}
