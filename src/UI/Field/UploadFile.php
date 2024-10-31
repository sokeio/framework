<?php

namespace Sokeio\UI\Field;


class UploadFile extends FieldUI
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
        $title = $this->getVar('title', 'Upload File', true);
        $description = $this->getVar('description', 'This is description', true);
        return <<<HTML
            <div {$attr} >
                <div class="dz-message  py-4">
                <h3 class="dropzone-msg-title">{$title}</h3>
                <span class="dropzone-msg-desc">{$description}</span>
                </div>
            </div>
        HTML;
    }
}
