<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Dashboard;
use BytePlatform\Tables\WithTablePageData;
use Livewire\Attributes\Reactive;

class WidgetTable extends Component
{
    #[Reactive]
    public $widgetId;
    #[Reactive]
    public $locked = false;

    protected function cardBodyClass()
    {
        return "p-0";
    }
    use WithTablePageData;
    protected function getListeners()
    {
        return [
            ...parent::getListeners(),
            'refreshData' . $this->widgetId => '__loadData',
        ];
    }
    protected function ItemManager()
    {
        if (!$this->widgetId) {
            return;
        }
        $WidgetSetting = Dashboard::getWidgetSettingByKey($this->widgetId);
        if ($WidgetSetting && isset($WidgetSetting['widgetType'])) {
            $dataWidget = Dashboard::getWidgetByKey($WidgetSetting['widgetType'])?->ClearCache()->Data($WidgetSetting)->beforeRender($this)->getWidgetData();
            if ($dataWidget && isset($dataWidget['manager'])) {
                return $dataWidget['manager'];
            }
        }
        return null;
    }
}
