<?php

namespace Sokeio\Concerns;


trait WithLoadMore
{
    public $dataItems = [];
    protected function getQuery(): mixed
    {
    }
    protected function getPageSize()
    {
        return 12;
    }
    public $page = 0;
    public function loadMore()
    {
        $this->page++;
        $query = $this->getQuery();
        $items = $query->paginate($this->getPageSize(), ['*'], 'page', $this->page)->items();
        if (count($items) == 0) {
            $this->page--;
            return;
        }
        $this->dataItems = array_merge($this->dataItems, $items);
        return $this->dataItems;
    }
    public function getLoadMoreHtml($text = 'Loading..')
    {
        return '<div wire:loading class="text-center p-2">' . $text . '</div>
    <div class="p-4" x-intersect.full="$wire.loadMore()"></div> ';
    }
}
