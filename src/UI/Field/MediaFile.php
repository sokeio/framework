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
    public function publicUrl()
    {
        return $this->vars('media-field', 'public_url');
    }
    public function multiple()
    {
        return $this->vars('multiple', true);
    }
    protected function fieldView()
    {
        $field = $this->getFieldName();
        $buttonText = $this->getVar('buttonText', 'Choose File', true);
        $buttonClass = $this->getVar('buttonClass', 'btn-primary', true);
        $multiple = $this->getVar('multiple', '', true);
        $mediaField = $this->getVar('media-field', 'public_url', true);
        $htmlPreview = <<<HTML
                <button x-show="FieldValue" style="display: none;"
                class="btn btn-danger" x-on:click="FieldValue = null">Remove</button>
            <div x-show="FieldValue" style="display: none;" class="sokeio-media-preview">
                <img class="sokeio-media-preview-item" x-bind:src="FieldValue" />
            </div>
        HTML;
        if ($multiple) {
            $multiple = 'multiple="true"';
            $htmlPreview = <<<HTML
            <button x-show="FieldValue" style="display: none;"
             class="btn btn-danger" x-on:click="FieldValue = null">Remove All</button>
            <div x-show="FieldValue" style="display: none;" class="sokeio-media-preview">
                <template x-if="FieldValue" x-for="file in FieldValue">
                <img class="sokeio-media-preview-item" x-bind:src="file" />
                </template>
            </div>
        HTML;
        }
        return <<<HTML
            <div class="sokeio-media-file">
                <button type="button" class="btn {$buttonClass}"
                 wire:media-file="{$field}" {$multiple} wire:media-field="{$mediaField}">
                    {$buttonText}
                </button>
               {$htmlPreview}
            </div>
        HTML;
    }
}
