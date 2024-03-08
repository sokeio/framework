<?php

namespace Sokeio\Concerns;


trait WithLoadMore
{
    public $dataItems = [];
    abstract protected function getQuery();
    protected function getPageSize()
    {
        return 12;
    }
    public $page = 0;
    public function loadMore()
    {
        $query = $this->getQuery();
        $items = $query->forPage($this->page + 1, $this->getPageSize())->get()->toArray();

        if (empty($items)) {
            return;
        }

        $this->dataItems = array_merge($this->dataItems, $items);
        $this->page++;
    }
    public function getLoadMoreHtml($text = 'Loading..')
    {
        return '<div wire:loading class="text-center p-2">' . $text . '</div>
    <div class="p-4" x-intersect.full="$wire.loadMore()"></div> ';
    }
}
