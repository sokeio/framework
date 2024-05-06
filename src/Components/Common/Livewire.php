<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithTitle;
use Sokeio\Components\Concerns\WithCollapse;

class Livewire extends BaseCommon
{
    use WithTitle, WithCollapse;
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
