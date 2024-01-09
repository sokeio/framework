<?php

namespace Sokeio\Admin\Components\Field;


class ModalField extends BaseField
{
    // $modalChoose = $column->getModalChoose();
    // $modalTitle = $column->getModalTitle();
    // $modalSize = $column->getModalSize();
    // $modal = $column->getModal();
    // $templateView = $column->getTemplate();

    public function ModalChoose($ModalChoose): static
    {
        return $this->setKeyValue('ModalChoose', $ModalChoose);
    }
    public function getModalChoose()
    {
        return $this->getValue('ModalChoose');
    }
    public function ModalTitle($ModalTitle): static
    {
        return $this->setKeyValue('ModalTitle', $ModalTitle);
    }
    public function getModalTitle()
    {
        return $this->getValue('ModalTitle');
    }
    public function ModalSize($ModalSize): static
    {
        return $this->setKeyValue('ModalSize', $ModalSize);
    }
    public function getModalSize()
    {
        return $this->getValue('ModalSize');
    }

    public function Modal($Modal): static
    {
        return $this->setKeyValue('Modal', $Modal);
    }
    public function getModal()
    {
        return $this->getValue('Modal');
    }
    public function Template($Template): static
    {
        return $this->setKeyValue('Template', $Template);
    }
    public function getTemplate()
    {
        return $this->getValue('Template');
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.choose-modal';
    }
}
