<?php

namespace Sokeio\Components\Concerns;

trait WithBaseBasic
{
    public function when($when):static
    {
        return $this->setKeyValue('when', $when);
    }
    public function getWhen()
    {
        return $this->getValue('when', true);
    }
    public function attribute($attribute): static
    {
        return $this->setKeyValue('attribute', $attribute);
    }
    public function getAttribute()
    {
        $attr = $this->getValue('attribute');
        if ($xInit = $this->getXInit()) {
            $attr = ' x-init="' . $xInit . '" ' . $attr;
        }
        if ($xData = $this->getXData()) {
            $attr = ' x-data="' . $xData . '" ' . $attr;
        }
        return $attr;
    }

    public function className($className):static
    {
        return $this->setKeyValue('className', $className);
    }
    public function getClassName()
    {
        return $this->getValue('className');
    }
    public function checkPrex()
    {
        return $this->checkKey('prex') && $this->getPrex() != '';
    }
    private $dataItem = null;
    public function dataItem($value): static
    {
        $this->dataItem = $value;
        $this->clearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->dataItem($value);
        }
        return $this;
    }
    public function getDataItem()
    {
        return $this->dataItem;
    }
    private $levelIndex = 0;
    public function levelIndex($value = 0)
    {
        $this->levelIndex = $value > 0 ? $value : $this->levelIndex + 1;
        $this->clearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->levelIndex($this->levelIndex);
        }
        return $this;
    }
    public function getLevelIndex()
    {
        return $this->levelIndex;
    }
    private $levelData = [];
    public function levelDataUI($value): static
    {
        $this->levelData = $value ?? [];
        $this->clearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->levelDataUI($this->levelData);
        }
        return $this;
    }
    public function getLevelDataUI()
    {
        return  $this->levelData;
    }
    public function levelData($value, $group_data = 'common', $_levelIndex = -1)
    {
        if ($_levelIndex < 0) {
            $_levelIndex = $this->levelIndex;
        }
        if (!isset($this->levelData[$_levelIndex])) {
            $this->levelData[$_levelIndex] = [];
        }
        $this->levelData[$_levelIndex][$group_data] = $value;
        return $this->levelDataUI($this->levelData);
    }
    public function getLevelData($group_data = 'common', $_levelIndex = -1)
    {
        if ($_levelIndex < 0) {
            $_levelIndex = $this->levelIndex;
        }
        if (!isset($this->levelData[$_levelIndex])) {
            $this->levelData[$_levelIndex] = [];
        }
        return isset($this->levelData[$_levelIndex][$group_data]) ? $this->levelData[$_levelIndex][$group_data] : null;
    }
    public function getEachData()
    {
        return $this->getLevelData('EachData');
    }
    public function getEachKey()
    {
        return $this->getLevelData('EachKey');
    }
    public function getEachIndex()
    {
        return $this->getLevelData('EachIndex');
    }
    public function prex($prex):static
    {
        return $this->setKeyValue('prex', $prex, true);
    }
    public function getPrex()
    {
        return $this->getValue('prex');
    }
    public function xData($xData):static
    {
        return $this->setKeyValue('xData', $xData);
    }
    public function getXData()
    {
        return $this->getValue('xData');
    }
    public function xInit($xInit):static
    {
        return $this->setKeyValue('xInit', $xInit);
    }
    public function getXInit()
    {
        return $this->getValue('xInit');
    }
}
