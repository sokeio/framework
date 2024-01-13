<?php

namespace Sokeio\Components;

use Sokeio\Laravel\BaseCallback;

class Base extends BaseCallback
{
    public function boot()
    {
    }
    protected function __construct($value)
    {
    }
    public static function Make($value)
    {
        return (new static($value));
    }
    public function When($When)
    {
        return $this->setKeyValue('When', $When);
    }
    public function getWhen()
    {
        return $this->getValue('When', true);
    }
    public function Attribute($Attribute)
    {
        return $this->setKeyValue('Attribute', $Attribute);
    }
    public function getAttribute()
    {
        return $this->getValue('Attribute');
    }

    public function ClassName($ClassName)
    {
        return $this->setKeyValue('ClassName', $ClassName);
    }
    public function getClassName()
    {
        return $this->getValue('ClassName');
    }
    public function checkPrex()
    {
        return $this->checkKey('Prex') && $this->getPrex() != '';
    }
    private $dataItem = null;
    public function DataItem($value)
    {
        $this->dataItem = $value;
        return $this;
    }
    public function getDataItem()
    {
        return $this->dataItem;
    }
    private $levelIndex = 0;
    public function LevelIndex($value = 0)
    {
        $this->levelIndex = $value > 0 ? $value : $this->levelIndex + 1;
        return $this;
    }
    public function getLevelIndex()
    {
        return $this->levelIndex;
    }
    private $levelData = [];
    public function LevelDataUI($value)
    {
        $this->levelData = $value ?? [];
        return $this;
    }
    public function getLevelDataUI()
    {
        return  $this->levelData;
    }
    public function LevelData($value, $group_data = 'common', $_levelIndex = -1)
    {
        if ($_levelIndex < 0) $_levelIndex = $this->levelIndex;
        if (!isset($this->levelData[$_levelIndex])) $this->levelData[$_levelIndex] = [];
        $this->levelData[$_levelIndex][$group_data] = $value;
        return $this->LevelDataUI($this->levelData);
    }
    public function getLevelData($group_data = 'common', $_levelIndex = -1)
    {
        if ($_levelIndex < 0) $_levelIndex = $this->levelIndex;
        if (!isset($this->levelData[$_levelIndex])) $this->levelData[$_levelIndex] = [];
        return isset($this->levelData[$_levelIndex][$group_data]) ? $this->levelData[$_levelIndex][$group_data] : null;
    }
    public function getEachData()
    {
        return $this->getLevelData('EachData');
    }
    public function getEachIndex()
    {
        return $this->getLevelData('EachIndex');
    }
    public function Prex($Prex)
    {
        return $this->setKeyValue('Prex', $Prex, true);
    }
    public function getPrex()
    {
        return $this->getValue('Prex');
    }
    public function getView()
    {
    }
}
