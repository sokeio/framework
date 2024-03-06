<?php

namespace Sokeio\Components;

use Sokeio\Component;
use Sokeio\Components\Concerns\WithFormSetingCallback;

class FormSettingCallback extends Component
{
    use WithFormSetingCallback;

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
        $this->loadDefault();
    }
    public function doSave()
    {
        if ($this->doValidate())
            $this->skipRender();
        $this->closeComponent();
        return $this->data;
    }
    protected function FooterUI()
    {
        return [
            UI::Div([
                UI::Button(__('Save'))->Attribute('@click="window[\'' . ($this->eventCallback) . '\'](await $wire.doSave())"')
            ])->ClassName('p-2 text-center')
        ];
    }
}
