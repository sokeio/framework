<?php

namespace Sokeio\Components;

use Sokeio\Laravel\BaseCallback;

class Base extends BaseCallback
{
    protected function ChildComponents()
    {
        return [];
    }
    private $cacheComponents;
    protected function ClearComponents($arr)
    {
        $result = [];
        if (!$arr) return $result;
        if (is_array($arr)) {
            foreach ($arr as $value) {
                if (is_array($value)) {
                    $result = [...$result, ...$this->ClearComponents($value)];
                } else if (is_a($value, Base::class)) {
                    $result[] = $value;
                }
            }
        } else {
            if (is_a($arr, Base::class)) {
                $result[] = $arr;
            }
        }

        return $result;
    }
    private function getChildComponents()
    {
        if (!isset($this->cacheComponents)) {
            $this->cacheComponents = $this->ClearComponents($this->ChildComponents());
        }
        return $this->cacheComponents;
    }
    public function boot()
    {
        $this->ClearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->Manager($this->getManager());
            $component->Prex($this->getPrex());
            $component->boot();
        }
    }
    protected function __construct($value)
    {
    }
    public static function Make($value)
    {
        return (new static($value));
    }
    public function actionUI($key, ...$arg)
    {
        if (method_exists($this->getManager(), 'addActionUI')) {
            return $this->getManager()->addActionUI($key, ...$arg);
        }
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
        $attr = $this->getValue('Attribute');
        if ($xInit = $this->getXInit()) {
            $attr = ' x-init="' . $xInit . '" ' . $attr;
        }
        if ($xData = $this->getXData()) {
            $attr = ' x-data="' . $xData . '" ' . $attr;
        }
        return $attr;
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
        $this->ClearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->DataItem($value);
        }
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
        $this->ClearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->LevelIndex($this->levelIndex);
        }
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
        $this->ClearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->LevelDataUI($this->levelData);
        }
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
    public function getEachKey()
    {
        return $this->getLevelData('EachKey');
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
    public function XData($XData)
    {
        return $this->setKeyValue('XData', $XData);
    }
    public function getXData()
    {
        return $this->getValue('XData');
    }
    public function XInit($XInit)
    {
        return $this->setKeyValue('XInit', $XInit);
    }
    public function getXInit()
    {
        return $this->getValue('XInit');
    }
}
