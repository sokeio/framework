<?php

namespace Sokeio\Components\Common;


class Livewire extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }

    public function Params($Params)
    {
        return $this->setKeyValue('Params', $Params);
    }
    public function getParams()
    {
        return $this->getValue('Params');
    }
    public function getView()
    {
        return 'sokeio::components.common.livewire';
    }
}
