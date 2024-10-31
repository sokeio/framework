<?php

namespace Sokeio\UI\Field;


class Textarea extends FieldUI
{
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
         <textarea {$attr}></textarea>
        HTML;
    }
}
