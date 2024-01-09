<?php

namespace Sokeio\Admin\Components\Common;

use Sokeio\Admin\Components\Base;

class BaseCommon extends Base
{
    protected function __construct($value)
    {
        $this->Content($value);
    }
    public function DataItem($value)
    {
        parent::DataItem($value);
        if (($content = $this->getContent())) {
            foreach ($content as $item) {
                if ($item) {
                    $item->DataItem($this->getDataItem());
                }
            }
        }
        return $this;
    }
    public function boot()
    {
        if (($content = $this->getContent())) {
            foreach ($content as $item) {
                if ($item) {
                    $item->Prex($this->getPrex());
                    $item->Manager($this->getManager());
                    $item->boot();
                }
            }
        }
        parent::boot();
    }
    public function Content($Content)
    {
        if (is_object($Content)) $Content = [$Content];
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
