<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Base;

class BaseCommon extends Base
{
    protected function __construct($value)
    {
        $this->Content($value);
    }
    public function DataItem($value)
    {
        $this->ClearCache();
        parent::DataItem($value);
        if (($content = $this->getContent())) {
            if (is_array($content)) {
                foreach ($content as $item) {
                    if ($item && is_a($item, Base::class)) {
                        $item->DataItem($this->getDataItem());
                    }
                }
            }
        }
        return $this;
    }
    public function LevelDataUI($value)
    {
        $this->ClearCache();
        parent::LevelDataUI($value);
        if (($content = $this->getContent())) {
            if (is_array($content)) {
                foreach ($content as $item) {
                    if ($item && is_a($item, Base::class)) {
                        $item->LevelDataUI($this->getLevelDataUI());
                    }
                }
            }
        }
        return $this;
    }
    public function boot()
    {
        if (($content = $this->getContent())) {
            if (is_array($content)) {
                foreach ($content as $item) {
                    if ($item && is_a($item, Base::class)) {
                        $item->Prex($this->getPrex());
                        $item->Manager($this->getManager());
                        $item->boot();
                    }
                }
            }
        }
        parent::boot();
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
