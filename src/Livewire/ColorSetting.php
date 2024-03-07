<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSettingCallback;
use Sokeio\Components\UI;

class ColorSetting extends FormSettingCallback
{

    protected function getColorList()
    {
        return apply_filters('SOKEIO_COLOR_SETTING', [
            "bg-blue text-blue-fg",
            "bg-azure text-azure-fg",
            "bg-indigo text-indigo-fg",
            "bg-purple text-purple-fg",
            "bg-pink text-pink-fg",
            "bg-red text-red-fg",
            "bg-orange text-orange-fg",
            "bg-yellow text-yellow-fg",
            "bg-lime text-lime-fg",
            "bg-green text-green-fg",
            "bg-teal text-teal-fg",
            "bg-cyan text-cyan-fg",
            "bg-dark text-dark-fg",
            "bg-muted text-muted-fg",
            "bg-blue-lt",
            "bg-azure-lt",
            "bg-indigo-lt",
            "bg-purple-lt",
            "bg-pink-lt",
            "bg-red-lt",
            "bg-orange-lt",
            "bg-yellow-lt",
            "bg-lime-lt",
            "bg-green-lt",
            "bg-teal-lt",
            "bg-cyan-lt",
            "bg-dark-lt",
            "bg-muted-lt",
            "text-blue bg-transparent",
            "text-azure bg-transparent",
            "text-indigo bg-transparent",
            "text-purple bg-transparent",
            "text-pink bg-transparent",
            "text-red bg-transparent",
            "text-orange bg-transparent",
            "text-yellow bg-transparent",
            "text-lime bg-transparent",
            "text-green bg-transparent",
            "text-teal bg-transparent",
            "text-cyan bg-transparent",
            "text-dark bg-transparent",
            "text-muted bg-transparent",
            "text-white bg-transparent",
            "bg-white-lt"
        ]);
    }
    protected function SettingUI()
    {
        return UI::Div([
            UI::Div(
                UI::Div('
            <template x-for="item in items">
                <div class="p-1 m-1 border rounded cursor-pointer"  x-data="{ hover: false }"
                @mouseenter="hover = true"
                @mouseleave="hover = false"
                @click="$wire.data.SettingValueField  = item"
                :class="hover || $wire.data.SettingValueField  === item? \'bg-azure\' : \'\'" >
                    <div class="p-3" :class="item" :title="item">Sokeio</div>
                </div>
            </template>
        ')->className('d-flex flex-wrap')
                    ->attribute('
        x-data="{
            items: ' . sokeioJS($this->getColorList())  . ',
        }"
        ')
            )->attribute(' style="height: 500px; overflow-y: scroll;" '),
            UI::Div('')->attribute('
                    x-text="$wire.data.SettingValueField"
                    x-show="$wire.data.SettingValueField"
                    :class="$wire.data.SettingValueField"
              ')
                ->className('p-2 m-1 mt-4 border rounded'),
        ]);
    }
}
