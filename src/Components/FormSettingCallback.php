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
    protected function formUI()
    {
        return UI::prex('data', $this->SettingUI());
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
        if ($this->doValidate()) {

            $this->skipRender();
        }
        $this->closeComponent();
        return $this->data;
    }
    protected function footerUI()
    {
        return [
            UI::Div([
                UI::button(__('Save'))
                    ->attribute('@click="window[\'' . ($this->eventCallback) . '\'](await $wire.doSave())"')
            ])->className('p-2 text-center')
        ];
    }
}
