<?php

namespace Sokeio\UI\Field;


class MediaFile extends FieldUI
{
    protected function initUI()
    {
        parent::initUI();
        return $this->render(function () {
            $this->className('dropzone dz-clickable text-center');
        });
    }
    public function buttonText($buttonText)
    {
        return $this->vars('buttonText', $buttonText);
    }
    public function buttonClass($buttonClass)
    {
        return $this->vars('buttonClass', $buttonClass);
    }
    public function multiple()
    {
        return $this->vars('multiple', true);
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $field = $this->getFieldName();
        $buttonText = $this->getVar('buttonText', 'Choose File', true);
        $buttonClass = $this->getVar('buttonClass', 'btn-primary', true);
        $multiple = $this->getVar('multiple', '', true);
        if ($multiple) {
            $multiple = 'multiple="true"';
        }
        return <<<HTML
            <div>
                <button type="button" class="btn {$buttonClass}"
                 wire:media-file="{$field}" {$multiple} >
                    {$buttonText}
                </button>
                <input type="hidden" {$attr}>
                <div class="sokeio-media-preview">
                    <div class="sokeio-media-preview-item" x-text="FieldValue"></div>
                    <template x-for="file in FieldValue">
                        <div class="sokeio-media-preview-item" x-text="file">
                            
                        </div>
                    </template>
                </div>
            </div>
        HTML;
    }
}
