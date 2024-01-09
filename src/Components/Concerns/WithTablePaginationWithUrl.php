<?php

namespace Sokeio\Admin\Components\Concerns;

use Livewire\Attributes\Url;

trait WithTablePaginationWithUrl
{
    #[Url()]
    public $paginators = [];
    #[Url()]
    public $pageSize;
}
