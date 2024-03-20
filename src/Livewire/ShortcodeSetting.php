<?php

namespace Sokeio\Livewire;

use Sokeio\Facades\Shortcode;
use Sokeio\Components\FormSettingCallback;
use Sokeio\Components\UI;

class ShortcodeSetting extends FormSettingCallback
{
    public function SettingUI()
    {
        $shortcode = Shortcode::getItemByKey($this->data->shortcode);
        $checkShort = $shortcode != null;
        return UI::row([
            UI::column([
                UI::select('shortcode')->label(__('Shortcode'))->dataSource(function () {
                    return [
                        [
                            'id' => '',
                            'name' => __('None')
                        ],
                        ...collect(Shortcode::getRegistered())->map(function ($item, $key) {
                            return [
                                'id' => $key,
                                'name' => ($item)::getTitle()
                            ];
                        })->toArray()
                    ];
                })->wireLive(),
                UI::Div([
                    UI::prex('data.attrs', function () use ($checkShort, $shortcode) {
                        return  $checkShort ? ($shortcode)::getParamUI() : [];
                    })->when(function () use ($checkShort) {
                        return  $checkShort;
                    }),
                ])->attribute(' wire:key="shortcode-' . $this->data->shortcode . '" '),

                UI::tinymce('children')->label(__('Content'))->when(function () use ($shortcode, $checkShort) {
                    return  $checkShort && ($shortcode)::withChild();
                })->when(function () use ($shortcode, $checkShort) {
                    return  $checkShort && ($shortcode)::withChild();
                }),
            ]),
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
                })
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
