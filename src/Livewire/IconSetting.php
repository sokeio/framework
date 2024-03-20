<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSettingCallback;
use Sokeio\Components\UI;
use Sokeio\Icon\IconManager;

class IconSetting extends FormSettingCallback
{
    public $searchText;
    public function getIcons($key)
    {
        foreach (sokeioIcons() as $item) {
            if ($item['key'] == $key) {
                return $item['items'];
            }
        }
        return [];
    }
    protected function SettingUI()
    {
        return [
            UI::Div([
                UI::Div('<template x-if="$wire.data.SettingValueField">
                <div >
                    <span style="font-size: 5rem"  :class="$wire.data.SettingValueField"></span>
                    <p class="fs-2 bg-warning text-warning-fg" x-text="$wire.data.SettingValueField"></p>
                     <button @click="$wire.data.SettingValueField = \'\'"
                      class="btn btn-sm btn-danger">Remove Icon</button>
                </div>
            </template>')
                    ->className('p-2 text-center bg-indigo text-indigo-fg')
                    ->attribute('x-show="$wire.data.SettingValueField"'),
                UI::Div('
                <template x-for="item in items">
                    <div class="p-2 m-2 border rounded cursor-pointer"  x-data="{ hover: false }"
                    @mouseenter="hover = true"
                    @mouseleave="hover = false"
                    @click="itemActive = item.key"
                    :class="hover || itemActive === item.key? \'bg-azure text-azure-fg\' : \'\'"
                    x-text="item.base"></div>
                </template>
                ')->className('d-flex flex-row'),
                UI::row([
                    UI::columnAuto(UI::text('searchText')->prex('')
                        ->label(__('Search Icon (<span x-text="icons?.length"></span>)'))
                        ->placeholder(__('Search Icon'))),
                ]),
                UI::Div('Loading...')
                    ->attribute('wire:loading wire:target="getIcons"')
                    ->className('text-center fs-2 text-azure'),
                UI::Div([
                    UI::Div('
                    <template x-for="icon in searchIcons()">
                        <div :title="icon.name"
                        class="p-2 m-1 border rounded cursor-pointer text-center"
                        x-data="{
                            hover: false,
                            checkHover() {
                                return hover || $wire.data.SettingValueField  === icon.icon;
                            }
                         }"
                        @mouseenter="hover = true"
                        @mouseleave="hover = false"
                        @click="$wire.data.SettingValueField = icon.icon"
                        :class="checkHover()? \'bg-azure text-azure-fg\' : \'\'"
                        >
                        <span class="fs-2" :class="icon.icon"></span>
                        </div>
                    </template>
                ')->className('d-flex flex-wrap')
                ])->attribute(' style="max-height: 300px; overflow-y: auto;" '),
            ])->attribute('
                    x-data="{
                        items: ' . sokeioJS(IconManager::getInstance()->getListBase()) . ',
                        itemActive: false,
                        iconActive: false,
                        icons: [],
                        async initIcon() {
                            $watch(\'itemActive\',async ($value)=>{
                                this.icons=[];
                                this.len = 200;
                                this.icons = await $wire.getIcons($value)
                            });
                            if(!this.itemActive) {
                                this.itemActive = this.items[0].key;
                            }
                        },
                        searchIcons(){
                            return this.icons.filter(item => item.name.includes( $wire.searchText)||!$wire.searchText);
                        }
                    }"

                    x-init="await initIcon()"
            '),

        ];
    }
}
