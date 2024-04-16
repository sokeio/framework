<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithName;
use Sokeio\Components\Common\Concerns\WithTitle;
use Sokeio\Components\Concerns\WithColumn;

class ButtonList extends BaseCommon
{
    use WithColumn;
    use WithTitle, WithName;
    public function getView()
    {
        return 'sokeio::components.common.button-list';
    }
    public function classButtonGroup($classButtonGroup)
    {
        return $this->setKeyValue('classButtonGroup', $classButtonGroup);
    }
    public function getClassButtonGroup()
    {
        return $this->getValue('classButtonGroup');
    }
}
