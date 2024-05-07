<?php

namespace Sokeio\Components\Field;


class TemplateField extends BaseField
{
    public function boot()
    {
        parent::boot();
        $this->convertEmptyStringsToNull();
    }
    public function templateView($templateView)
    {
        return $this->setKeyValue('templateView', $templateView);
    }
    public function getTemplateView()
    {
        return $this->getValue('templateView');
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.template';
    }
}
