<?php

namespace Sokeio\Widget;

use Sokeio\ILoader;
use Sokeio\Core\ItemInfo;
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
        return $this->info ?? ($this->info = WidgetInfo::fromClass(self::class));
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            $poll = $this->getDataParam('poll');
            if ($poll) {
                $this->action('poll', function () use ($poll) {
                    return $poll;
                });
            }
        });
    }

    public static function paramUI()
    {
        return [];
    }
}
