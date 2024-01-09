<?php

namespace Sokeio\Components\Common;


class Row extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.row';
    }
}

