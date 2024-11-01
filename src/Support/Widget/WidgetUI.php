<?php

namespace Sokeio\Support\Widget;

use Sokeio\ILoader;
use Sokeio\Support\Platform\ItemInfo;
use Sokeio\UI\BaseUI;
use Sokeio\Widget;

class WidgetUI extends BaseUI implements ILoader
{
    public static function runLoad(ItemInfo $itemInfo)
    {
        Widget::registerClass(static::class);
    }
    private $info;
    private $dataParam = [];
    public function setDataParam($data)
    {
        $this->dataParam = $data;
        return $this;
    }
    public function getDataParam($key, $default = '')
    {
        return data_get($this->dataParam, $key, $default);
    }
    public function getInfo(): WidgetInfo
    {
        return $this->info ?? ($this->info = WidgetInfo::getInfoFromUI(self::class));
    }


    public static function paramUI()
    {
        return [];
    }
}
