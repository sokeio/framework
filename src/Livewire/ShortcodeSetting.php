<?php

namespace Sokeio\Livewire;

use Sokeio\Facades\Shortcode;
use Sokeio\Components\FormSettingCallback;
use Sokeio\Components\UI;

class ShortcodeSetting extends FormSettingCallback
{
    public $arrShortcodes = [];
    public function mount()
    {
        parent::mount();
        $this->arrShortcodes = collect(Shortcode::getRegistered())->map(function ($item, $key) {
            return [
                'id' => $key,
                'name' => ($item)::getTitle(),
                'icon' => ($item)::getIcon(),
                'image' => ($item)::getImage()
            ];
        })->toArray();
    }
    public function changeShortcode($id)
    {
        $this->data->shortcode = $id;
    }
    public function SettingUI()
    {
        $shortcode = Shortcode::getItemByKey($this->data->shortcode);
        $checkShort = $shortcode != null;
        return UI::row([
            UI::column([
                UI::div('
                <h3>Choose Shortcode</h3>
                <template x-for="item in $wire.arrShortcodes">
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2"  x-data="{ hover: false }">
                        <div class="p-4 border rounded cursor-pointer"  x-data="{ hover: false }"
                            @mouseenter="hover = true"
                            @mouseleave="hover = false"
                            @click="changeShortcode(item.id)"
                            
                            :class="hover || $wire.data.shortcode === item.id? \'bg-azure\' : \'\'" >
                            <div class="text-center">
                            <i x-show="!item.image" :class="item.icon" style="font-size: 5rem"></i>
                            <img x-show="item.image" :src="item.image" style="width: 5rem">
                            </div>
                            <div class="text-center mt-2" x-text="item.name"></div>
                        </div>
                    </div>
                </template>
                ')->className('row')->attribute(' x-show="!$wire.data.shortcode" '),
                UI::div('
                <div wire:loading.delay wire:target="changeShortcode"
                class="spinner-border spinner-border-sm mx-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="input-group mb-2" wire:loading.add.class="d-none" wire:target="changeShortcode" >
                    <input type="text" class="form-control bg-danger text-bg-danger"
                     readonly x-model="$wire.data.shortcode">
                    <button class="btn" type="button" @click="changeShortcode(\'\')">
                        <i class="ti ti-replace"></i> <span class="ps-1">Change</span>
                    </button>
                </div>')
                    ->attribute(' x-show="$wire.data.shortcode"'),
                UI::Div([
                    UI::prex('data.attrs', function () use ($checkShort, $shortcode) {
                        return  $checkShort ? ($shortcode)::getParamUI() : [];
                    })->when(function () use ($checkShort) {
                        return  $checkShort;
                    }),
                    UI::tinymce('children')->label(__('Content'))->when(function () use ($shortcode, $checkShort) {
                        return  $checkShort && ($shortcode)::withChild();
                    }),
                ])->attribute(' x-show="$wire.data.shortcode" '),


            ])->xData("{
                changeShortcode(id){
                    \$wire.data.shortcode = id
                    \$wire.changeShortcode(id)
                }
            }"),
            UI::column7([
                UI::Div([
                    UI::Div("")
                        ->className('p-2 border rounded bg-blue text-blue-fg align-self-stretch me-2 w-100')
                        ->attribute('x-html="shortcode"'),
                    UI::button('Preview')->warning()->xClick('doPreview()')
                ])->className('d-flex'),
                UI::Div([
                    UI::Div('')->attribute('
                    x-html="shortcodeHtml"
                     style="min-height: 200px;max-height: 400px;overflow: auto"
                     '),
                ])->className('mt-2 p-2 border rounded bg-dark-lt position-relative'),
            ])->xData("{
                shortcode: '" . $this->getShortCodeHtml() . "',
                shortcodeHtml: '',
                shortcodeWireId: '',
                async doPreview() {
                    let rs = await this.\$wire.doPreview();
                    if (this.shortcodeWireId != '') {
                        Livewire.find(this.shortcodeWireId)?.__instance?.cleanup();
                    }
                    this.shortcode = rs['shortcode'];
                    this.shortcodeHtml = rs['shortcodeHtml'];
                    this.shortcodeWireId = rs['wireId'];
                }
            }")->xInit('doPreview()')
                ->when(function () use ($checkShort) {
                    return $checkShort;
                })->attribute(' x-show="$wire.data.shortcode" '),
        ]);
    }
    private function getShortCodeHtml()
    {
        if ($this->data->shortcode == '') {
            return '';
        }
        $html = '[' . $this->data->shortcode;
        if ($items = $this->getAllInputUI()) {
            foreach ($items as $item) {
                if (in_array($item->getName(), ['shortcode', 'children'])) {
                    continue;
                }
                $value = data_get($this, $item->getFormFieldEncode(), $item->getValueDefault());
                if ($value) {
                    $html .= ' ' . $item->getName() . '="' . $value . '"';
                }
            }
        }
        if ($this->data->children) {
            $html .= ']' . $this->base64Encode($this->data->children) . '[/' . $this->data->shortcode . ']';
        } else {

            $html .= '/]';
        }
        return   $html;
    }
    public function getShortCodeHtml2()
    {
        $this->closeComponent();
        $this->skipRender();
        return $this->getShortCodeHtml();
    }
    public function doPreview()
    {
        $this->skipRender();
        $shortcode = $this->getShortCodeHtml();

        Shortcode::enable();
        $shortcodeHtml = shortcodeRender($shortcode);
        $pattern = '/wire:id="([^"]+)"/';
        $wireId = '';
        if (preg_match($pattern, $shortcodeHtml, $matches)) {
            $wireId = $matches[1];
        }
        return [
            'shortcode' => $shortcode,
            'shortcodeHtml' => $shortcodeHtml,
            'wireId' => $wireId
        ];
    }
    public function doSave()
    {
        if ($this->doValidate()) {
            $this->skipRender();
        }
        $this->closeComponent();
        return $this->getShortCodeHtml();
    }
}
