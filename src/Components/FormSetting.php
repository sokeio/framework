<?php

namespace Sokeio\Components;

use Illuminate\Support\Facades\DB;
use Sokeio\Components\Concerns\WithForm;
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
        if (method_exists($this, 'loadDataBefore')) {
            call_user_func([$this, 'loadDataBefore']);
        }
        foreach ($this->getAllInputUI() as $column) {
            $this->LoadSetting($column->getFormFieldEncode(), $column->getNameEncode(), $column);
        }
        if (method_exists($this, 'loadDataAfter')) {
            call_user_func([$this, 'loadDataAfter']);
        }
    }
    protected function LoadSetting($keyForm, $keyValue, $column)
    {
        data_set($this, $keyForm, setting($keyValue, $column->getValueDefault()));
        return $this;
    }
    protected function SaveSetting($keyForm, $keyValue, $column)
    {
        set_setting($keyValue, data_get($this, $keyForm));
        return $this;
    }
    public function doSave()
    {
        $this->doValidate();

        DB::transaction(function () {
            if (method_exists($this, 'saveDataBefore')) {
                call_user_func([$this, 'saveDataBefore']);
            }
            foreach ($this->getAllInputUI() as $column) {
                $this->SaveSetting($column->getFormFieldEncode(), $column->getNameEncode(), $column);
            }
            if (method_exists($this, 'saveDataAfter')) {
                call_user_func([$this, 'saveDataAfter']);
            }
        });

        $this->showMessage($this->formMessage(false));
        if (!$this->CurrentIsPage()) {
            $this->refreshRefComponent();
            $this->closeComponent();
        } else {
            return  $this->redirectCurrent();
        }
    }
}
