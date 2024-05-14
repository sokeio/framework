<?php

namespace Sokeio\Livewire\Dashboard;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Session;
use Sokeio\Components\Form;
use Sokeio\Components\UI;

class FormWidgetSetting extends Form
{
    public $dashboardId;
    #[Session('widget_settings')]
    public $widgets;
    private $widgetInst;
    public function bootInitLayout()
    {
        if (!$this->dataId) {
            $this->dataId = request('dataId');
        }
        if (!$this->dashboardId) {
            $this->dashboardId = request('dashboardId');
        }
    }
    protected function getDataRow()
    {
        return $this->getWidget()->getOption();
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
        $widget = collect($this->widgets)->where('id', $this->dataId)->first();
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
            $classWidget = null;
            if ($widget) {
                $classWidget = $widget['class'];
            }
            if ($classWidget && class_exists($classWidget) && $temp = app($classWidget)) {
                $this->widgetInst = $temp->boot()
                    ->component($this)
                    ->option($widget['options'] ?? []);
            }
        }
        return $this->widgetInst;
    }
    protected function formUI()
    {
        return UI::prex('data', UI::row(function () {
            return $this->getWidget()->getParamUI();
        }))->className('p-3');
    }
}
