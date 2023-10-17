<?php

namespace BytePlatform\Builders\Form;

use BytePlatform\Builders\HtmlBuilder;

class FormBuilder extends HtmlBuilder
{
    public function __construct(private FormItem $formItem)
    {
    }
    protected function render()
    {
        $this->formItem->beforeRender();
        if (!apply_filters(PLATFORM_FORM_RENDER, false, $this->formItem)) {
            echo view_scope('byte::builder.form', [
                'form' => $this->formItem
            ])->render();
        }
    }
}
