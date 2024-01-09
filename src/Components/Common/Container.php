<?php

namespace Sokeio\Admin\Components\Common;


class Container extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.container';
    }
}
