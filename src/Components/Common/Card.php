<?php

namespace Sokeio\Admin\Components\Common;


class Card extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
   
    public function Title($Title)
    {
        return $this->setKeyValue('Title', $Title);
    }
    public function getTitle()
    {
        return $this->getValue('Title');
    }
    public function getView()
    {
        return 'sokeio::components.common.box';
    }
}
