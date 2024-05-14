<?php

namespace Sokeio\Livewire\Dashboard;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Session;
use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Facades\Dashboard;

class FormWidgetSetting extends Form
{
    public $dashboardId;
    #[Session('widget_settings')]
    public $widgets;
    public $widgetType;
    public $position;
    private $widgetInst;
    public function mount()
    {
        parent::mount();
        $this->widgetType = $this->getWidget()?->getWidgetType();
    }
    public function bootInitLayout()
    {
        if (!$this->dataId) {
            $this->dataId = request('dataId');
        }
        if (!$this->dashboardId) {
            $this->dashboardId = request('dashboardId');
        }
        if (!$this->position) {
            $this->position = request('position');
        }
    }
    protected function getDataRow()
    {
        return $this->getWidget()?->getOption() ?? [];
    }
    protected function getDataObject()
    {
        return $this->getDataRow();
    }
    public function doSave()
    {
        if (!$this->doValidate()) {
            if (method_exists($this, 'validateFail')) {
                call_user_func([$this, 'validateFail']);
            }
            return;
        }
        $objData = $this->getDataObject();
        $objData = $this->fillToModel($objData);
        $widget = collect($this->widgets)->where('id', $this->dataId)->first() ?? [];
        if (!$this->dataId) {
            $widget['position'] = $this->position;
            $widget['dashboardId'] = $this->dashboardId;
            $widget['type'] = $this->widgetType;
        }
        $widget['options'] = $objData;
        $this->callFuncByRef('changeWidget', $widget);

        if ($this->skipClose === false) {
            $this->showMessage($this->formMessage(false));
            $this->doRefreshRef();
        }
    }

    private function getWidget()
    {
        if (!$this->widgetInst) {
            $widget = collect($this->widgets)->where('id', $this->dataId)->first();
            Log::info([$this->widgets, $this->dataId, $widget]);
            if ($widget && isset($widget['type'])) {
                $this->widgetType = $widget['type'];
            }
            $classWidget = null;
            if ($this->widgetType) {
                $classWidget = Dashboard::getWidgetClassByKey($this->widgetType);
            }
            if ($classWidget && class_exists($classWidget) && $temp = app($classWidget)) {
                $this->widgetInst = $temp->boot()
                    ->component($this)
                    ->option($widget['options'] ?? []);
            }
        }
        return $this->widgetInst;
    }
    protected function checkWidget()
    {
        return ($this->dataId && $this->dataId > 0) || $this->widgetType;
    }
    protected function formUI()
    {
        return UI::div([
            UI::select('widgetType')->label(__('Widget Type'))->wireLive()->dataSource(function () {
                return [
                    [
                        'id' => '',
                        'title' => __('None')
                    ],
                    ...Dashboard::getWidgetType()
                ];
            })->disable(function () {
                if ($this->dataId && $this->dataId > 0) {
                    return true;
                }
                return false;
            })->noSave(),
            UI::prex('data', UI::row(function () {
                return $this->getWidget()?->getParamUI() ?? [];
            }))->when(function () {
                return  $this->checkWidget();
            })
        ])->className('p-3');
    }
}
