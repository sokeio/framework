<?php

namespace BytePlatform\Builder\Form;

use BytePlatform\Builder\HtmlBuilder;

class FieldBuilder extends HtmlBuilder
{
    public function __construct(private FieldItem $fieldItem, private FormItem $formItem)
    {
    }
    protected function render()
    {
        if (!apply_filters(PLATFORM_FIELD_RENDER, false, $this)) {
            echo view_scope('byte::builder.field', [
                'field' => $this->fieldItem,
                'form' => $this->formItem
            ])->render();
        }
    }
}
