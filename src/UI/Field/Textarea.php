<?php

namespace Sokeio\UI\Field;


class Textarea extends FieldUI
{
    protected function initUI()
    {
        parent::initUI();
        $this->showCount();
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
         <textarea {$attr}></textarea>
        HTML;
    }
}
