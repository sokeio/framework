<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithName;
use Sokeio\Components\Common\Concerns\WithTitle;
use Sokeio\Components\Concerns\WithColumn;

class ButtonGroup extends BaseCommon
{
    use WithColumn;
    use WithTitle,WithName;
    public function getView()
    {
        return 'sokeio::components.common.button-group';
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
