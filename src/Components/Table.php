<?php

namespace Sokeio\Admin\Components;

use Sokeio\Admin\Components\Concerns\WithTable;
use Sokeio\Component;

class Table extends Component
{
    use  WithTable;
    public function Booted()
    {
        if (!$this->selectIds) {
            $this->selectIds = request('selectIds') ?? [];
        }
        parent::Booted();
    }
}
