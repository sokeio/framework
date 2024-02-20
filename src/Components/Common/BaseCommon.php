<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Base;

class BaseCommon extends Base
{
    protected function __construct($value)
    {
        $this->Content($value);
    }
    protected function ChildComponents()
    {
        return $this->getContent();
    }
    public function Content($Content)
    {
        if ($Content && is_a($Content, Base::class)) {
            $Content = [$Content];
        }
        return $this->setKeyValue('Content', $Content);
    }
    public function getContent()
    {
        return $this->getValue('Content');
    }
    public function AttributeBox($AttributeBox)
    {
        return $this->setKeyValue('AttributeBox', $AttributeBox);
    }
    public function getAttributeBox()
    {
        return $this->getValue('AttributeBox');
    }
    
}
