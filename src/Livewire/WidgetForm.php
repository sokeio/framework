<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Dashboard;
use BytePlatform\Forms\WithFormData;
use Livewire\Attributes\Reactive;

class WidgetForm extends Component
{
    #[Reactive]
    public $widgetId;
    #[Reactive]
    public $locked = false;

    use WithFormData;
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
        if ($WidgetSetting)
            return Dashboard::getWidgetByKey($WidgetSetting['widgetType'])?->ClearCache()->Data($WidgetSetting)->beforeRender($this)->getWidgetData()['manager'];
        return null;
    }
}
