<?php

namespace Sokeio\Components;

use Sokeio\Components\Concerns\WithTable;
use Sokeio\Component;

abstract class Table extends Component
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
