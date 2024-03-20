<?php

namespace Sokeio\Components\Field;


class ModalField extends BaseField
{

    public function modalChoose($modalChoose): static
    {
        return $this->setKeyValue('modalChoose', $modalChoose);
    }
    public function getModalChoose()
    {
        return $this->getValue('modalChoose');
    }
    public function modalTitle($modalTitle): static
    {
        return $this->setKeyValue('modalTitle', $modalTitle);
    }
    public function getModalTitle()
    {
        return $this->getValue('modalTitle');
    }
    public function modalSize($modalSize): static
    {
        return $this->setKeyValue('modalSize', $modalSize);
    }
    public function getModalSize()
    {
        return $this->getValue('modalSize');
    }

    public function modal($modal): static
    {
        return $this->setKeyValue('modal', $modal);
    }
    public function getModal()
    {
        return $this->getValue('modal');
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
