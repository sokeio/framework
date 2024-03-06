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
        // if (!$shortcode) return UI::Div($shortcode . ' shortcode not found');
        // if (!class_exists($shortcode)) return UI::Div($shortcode);
        return UI::Row([
            UI::Column([
                UI::Select('shortcode')->Label(__('Shortcode'))->DataSource(function () {
                    return [
                        [
                            'id' => '',
                            'name' => __('None')
                        ],
                        ...collect(Shortcode::getRegistered())->map(function ($item, $key) {
                            return [
                                'id' => $key,
                                'name' => ($item)::getShortcodeName()
                            ];
                        })->toArray()
                    ];
                })->WireLive(),
                UI::Prex('data.attrs', function () use ($shortcode) {
                    return (!!$shortcode) ? ($shortcode)::getShortcodeParamUI() : [];
                })->When(function () use ($shortcode) {
                    return !!($shortcode);
                }),
                UI::Tinymce('children')->Label(__('Content'))->When(function () use ($shortcode) {
                    return $this->data->shortcode != '' && ($shortcode)::EnableContent();
                }),
            ]),
            UI::Column7([
                UI::Div([
                    UI::Div(function () {
                        return;
                    })->ClassName('p-2 border rounded bg-blue text-blue-fg align-self-stretch me-2 w-100')->Attribute('x-html="shortcode"'),
                    UI::Button('Preview')->Warning()->XClick('doPreview()')
                ])->ClassName('d-flex'),
                UI::Div([
                    UI::Div('')->Attribute('x-html="shortcodeHtml" style="min-height: 200px;max-height: 400px;overflow: auto"'),
                ])->XData("{
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
                }")->XInit('doPreview()')->ClassName('mt-2 p-2 border rounded bg-dark-lt position-relative'),
            ])->XData("{
                shortcode: '" . $this->getShortCodeHtml() . "',
            }")
                ->When(function () {
                    return $this->data->shortcode != '';
                })
        ]);
    }
    private function getShortCodeHtml()
    {
        if ($this->data->shortcode == '') return '';
        $html = '[' . $this->data->shortcode;
        if ($items = $this->getAllInputUI()) {
            foreach ($items as $item) {
                if (in_array($item->getName(), ['shortcode', 'children'])) continue;
                $value = data_get($this, $item->getFormFieldEncode(), $item->getValueDefault());
                if ($value) {
                    $html .= ' ' . $item->getName() . '="' . $value . '"';
                }
            }
        }
        if ($this->data->children) {
            $html .= ']' . $this->Base64Encode($this->data->children) . '[/' . $this->data->shortcode . ']';
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
        $shortcodeHtml = shortcode_render($shortcode);
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
        if ($this->doValidate())
            $this->skipRender();
        $this->closeComponent();
        return $this->getShortCodeHtml();
    }
}
