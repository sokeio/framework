<?php

namespace Sokeio\Components\Common;


class Each extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.each';
    }
    public function ArrayData($ArrayData)
    {
        return $this->setKeyValue('ArrayData', $ArrayData);
    }
    public function getArrayData()
    {
        return $this->getValue('ArrayData');
    }
}
