<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Common\Concerns\WithButtonSoke;

class ModalField extends BaseField
{
    use WithButtonSoke;
    public function hideData($hideData=true): static
    {
        return $this->setKeyValue('hideData', $hideData);
    }
    public function getHideData()
    {
        return $this->getValue('hideData');
    }

    public function modalChoose($modalChoose): static
    {
        return $this->setKeyValue('modalChoose', $modalChoose);
    }
    public function getModalChoose()
    {
        return $this->getValue('modalChoose');
    }
    public function template($template): static
    {
        return $this->setKeyValue('template', $template);
    }
    public function getTemplate()
    {
        return $this->getValue('template');
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.choose-modal';
    }
}
