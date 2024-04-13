<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithColumnGrid;

class Column extends BaseCommon
{
    use WithColumnGrid;
    protected function __construct($value)
    {
        parent::__construct($value);
        $this->col();
    }
  
    public function getView()
    {
        return 'sokeio::components.common.column';
    }
}
