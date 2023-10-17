<?php

namespace BytePlatform\Concerns;

use Livewire\WithPagination as WithPaginationBase;

trait WithPagination
{
    use WithPaginationBase;
    // public function queryStringHandlesPagination()
    // {
    //     if (method_exists($this, 'CurrentIsPage') && $this->CurrentIsPage()) {
    //         return  $this->queryStringHandlesPaginationBase();
    //     }
    //     return  collect($this->paginators)->mapWithKeys(function ($page, $pageName) {
    //         return ['paginators.' . $pageName => ['history' => false, 'as' => $pageName, 'keep' => true]];
    //     })->toArray();
    // }
}
