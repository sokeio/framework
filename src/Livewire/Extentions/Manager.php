<?php

namespace Sokeio\Livewire\Extentions;

use Livewire\WithPagination;
use Sokeio\Component;

class Manager extends Component
{
    use WithPagination;
    public $ExtentionType;
    public $pageSize = 10;
    public function ItemChangeStatus($itemId, $status)
    {
        platformBy($this->ExtentionType)->find($itemId)->status = $status;
        return  $this->redirectCurrent();
    }
    public function render()
    {
        return view('sokeio::extentions.manager', [
            'dataItems' => collect(platformBy($this->ExtentionType)->getDataAll())->paginate($this->pageSize),
            'pageSizeList' => [5, 10, 20, 50]
        ]);
    }
}
