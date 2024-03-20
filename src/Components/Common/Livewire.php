<?php

namespace Sokeio\Components\Common;


class Livewire extends BaseCommon
{

    public function params($params)
    {
        return $this->setKeyValue('params', $params);
    }
    public function getParams()
    {
        return $this->getValue('params');
    }
    public function getView()
    {
        return 'sokeio::components.common.livewire';
    }
}
