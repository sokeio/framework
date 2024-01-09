<?php

namespace Sokeio\Components\Common;


class ButtonGroup extends BaseCommon
{
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
}
