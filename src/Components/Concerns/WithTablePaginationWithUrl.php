<?php

namespace Sokeio\Components\Concerns;

use Livewire\Attributes\Url;

trait WithTablePaginationWithUrl
{
    #[Url()]
    public $paginators = [];
    #[Url()]
    public $pageSize;
}
