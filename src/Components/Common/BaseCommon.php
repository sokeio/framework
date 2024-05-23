<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Base;

class BaseCommon extends Base
{
    public function getGroup()
    {
        return 'common';
    }
    protected function __construct($value)
    {
        $this->content($value);
    }
    protected function ChildComponents()
    {
        return $this->getContent();
    }
    public function content($content)
    {
        if ($content && is_a($content, Base::class)) {
            $content = [$content];
        }
        return $this->setKeyValue('content', $content);
    }
    public function getContent()
    {
        return $this->getValue('content');
    }
    public function attributeBox($attributeBox)
    {
        return $this->setKeyValue('attributeBox', $attributeBox);
    }
    public function getAttributeBox()
    {
        return $this->getValue('attributeBox');
    }
}
