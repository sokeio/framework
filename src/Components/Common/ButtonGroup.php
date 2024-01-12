<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Concerns\WithColumn;

class ButtonGroup extends BaseCommon
{
    use WithColumn;
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.button-group';
    }
    public function Title($Title)
    {
        return $this->setKeyValue('Title', $Title);
    }
    public function getTitle()
    {
        return $this->getValue('Title');
    }
    public function ClassButtonGroup($ClassButtonGroup)
    {
        return $this->setKeyValue('ClassButtonGroup', $ClassButtonGroup);
    }
    public function getClassButtonGroup()
    {
        return $this->getValue('ClassButtonGroup');
    }
    public function Name($Name)
    {
        return $this->setKeyValue('Name', $Name);
    }
    public function getName()
    {
        return $this->getValue('Name');
    }
    public function getFieldValue($row)
    {
        $this->ClearCache();
        $this->DataItem($row);
        // if ($this->fieldValueCallback) return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        $this->Title(data_get($row, $this->getName()));
        return view($this->getView(), [
            'column' => $this
        ])->render();
    }
}
