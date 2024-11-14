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
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function description($description)
    {
        return $this->vars('description', $description);
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $field = $this->getFieldName();
        return <<<HTML
            <div>
                <button type="button" class="btn btn-primary" wire:media-file="{$field}" >Choose File</button>
                <input type="hidden" {$attr} wire:model="{$field}">
            </div>
        HTML;
    }
}
