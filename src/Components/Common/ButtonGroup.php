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

    public function getFieldValue($row)
    {
        $this->ClearCache();
        $this->dataItem($row);
        if ($this->fieldValueCallback) {
            return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        }
        $this->title(data_get($row, $this->getName()));
        return view($this->getView(), [
            'column' => $this
        ])->render();
    }
}
