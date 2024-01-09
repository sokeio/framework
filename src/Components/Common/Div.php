<?php

namespace Sokeio\Components\Common;


class Div extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.div';
    }
}
