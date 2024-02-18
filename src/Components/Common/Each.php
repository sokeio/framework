<?php

namespace Sokeio\Components\Common;


class Each extends BaseCommon
{
    protected $childContentEach;
    public function getChildContentEach()
    {
        return $this->childContentEach;
    }
    protected function ChildComponents()
    {
        if (($ArrayData = $this->getArrayData())) {
            $contents = $this->ClearComponents($this->getContent());
            $childs = [];
            $rowIndex = 0;
            foreach ($ArrayData as $key => $item) {
                foreach ($contents as $value) {
                    $childContent = clone $value;
                    $childContent->LevelData($item, 'EachData');
                    $childContent->LevelData($key, 'EachKey');
                    $childContent->LevelData($rowIndex, 'EachIndex');
                    $childs[] = $childContent;
                }
                $rowIndex++;
            }

            $this->childContentEach =  $childs;
            return  $childs;
        }
        return [];
    }
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
