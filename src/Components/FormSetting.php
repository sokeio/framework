<?php

namespace Sokeio\Admin\Components;

use Illuminate\Support\Facades\DB;
use Sokeio\Admin\Components\Concerns\WithForm;
use Sokeio\Component;

class FormSetting extends Component
{
    use  WithForm;
    protected function getFormClass()
    {
        return 'card p-3';
    }
    protected function FormUI()
    {
        return UI::Prex('data', $this->SettingUI());
    }
    protected function SettingUI()
    {
        return [];
    }
    public function loadData()
    {
        if (method_exists($this, 'loadDataAfter')) {
            call_user_func([$this, 'loadDataAfter']);
        }
        foreach ($this->getColumns() as $column) {
            data_set($this, $column->getFormFieldEncode(), setting($column->getNameEncode()));
        }
    }
    public function doSave()
    {
        $this->doValidate();
        DB::transaction(function () {
            foreach ($this->getColumns() as $column) {
                set_setting($column->getNameEncode(), data_get($this, $column->getFormFieldEncode()));
            }
        });
        $this->showMessage($this->formMessage(false));
        if (!$this->CurrentIsPage()) {
            $this->refreshRefComponent();
            $this->closeComponent();
        }
    }
}
