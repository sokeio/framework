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
        if ($arrayData = $this->getArrayData()) {
            $contents = $this->ClearComponents($this->getContent());
            $childs = [];
            $rowIndex = 0;
            foreach ($arrayData as $key => $item) {
                foreach ($contents as $value) {
                    $childContent = clone $value;
                    $childContent->levelData($item, 'EachData');
                    $childContent->levelData($key, 'EachKey');
                    $childContent->levelData($rowIndex, 'EachIndex');
                    $childs[] = $childContent;
                }
                $rowIndex++;
            }

            $this->childContentEach =  $childs;
            return  $childs;
        }
        return [];
    }
    public function getView()
    {
        return 'sokeio::components.common.each';
    }
    public function arrayData($arrayData)
    {
        return $this->setKeyValue('arrayData', $arrayData);
    }
    public function getArrayData()
    {
        return $this->getValue('arrayData');
    }
}
