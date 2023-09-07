<?php

namespace BytePlatform;

class Button extends ItemCallback
{
    private function __construct($title)
    {
        $this->Title($title)
            ->View('byte::button')
            ->When(function () {
                return true;
            });
    }
    public static function Create($title)
    {
        return new self($title);
    }
    public function Class($class)
    {
        return $this->setKeyValue('class', $class);
    }
    public function getClass()
    {
        return $this->getValue('class');
    }
    public function ButtonSize($buttonSize)
    {
        return $this->setKeyValue('buttonSize', $buttonSize);
    }
    public function getButtonSize()
    {
        return $this->getValue('buttonSize');
    }
    public function ButtonType($buttonType)
    {
        return $this->setKeyValue('buttonType', $buttonType);
    }
    public function getButtonType()
    {
        return $this->getValue('buttonType');
    }
    public function WireClick($wireClick)
    {
        return $this->setKeyValue('wireClick', $wireClick);
    }
    public function getWireClick()
    {
        return $this->getValue('wireClick');
    }
    public function Attribute($attribute)
    {
        return $this->setKeyValue('attribute', $attribute);
    }
    public function getAttribute()
    {
        return $this->getValue('attribute');
    }
    public function Title($title)
    {
        return $this->setKeyValue('title', $title);
    }
    public function getTitle()
    {
        return $this->getValue('title');
    }
    public function ModalUrl($modalUrl)
    {
        return $this->setKeyValue('modalUrl', $modalUrl);
    }
    public function getModalUrl()
    {
        return $this->getValue('modalUrl');
    }
    public function ModalSize($modalSize)
    {
        return $this->setKeyValue('modalSize', $modalSize);
    }
    public function getModalSize()
    {
        return $this->getValue('modalSize');
    }
    public function ModalTitle($modalTitle)
    {
        return $this->setKeyValue('modalTitle', $modalTitle);
    }
    public function getModalTitle()
    {
        return $this->getValue('modalTitle');
    }

    public function Confirm($confirm)
    {
        return $this->setKeyValue('confirm', $confirm);
    }
    public function getConfirm()
    {
        return $this->getValue('confirm');
    }
    public function ConfirmTitle($confirmTitle)
    {
        return $this->setKeyValue('confirmTitle', $confirmTitle);
    }
    public function getConfirmTitle()
    {
        return $this->getValue('confirmTitle');
    }

    public function ConfirmNo($confirmNo)
    {
        return $this->setKeyValue('confirmNo', $confirmNo);
    }
    public function getConfirmNo()
    {
        return $this->getValue('confirmNo');
    }
    public function ConfirmYes($confirmYes)
    {
        return $this->setKeyValue('confirmYes', $confirmYes);
    }
    public function getConfirmYes()
    {
        return $this->getValue('confirmYes');
    }
    public function View($view)
    {
        return $this->setKeyValue('view', $view);
    }
    public function getView()
    {
        return $this->getValue('view');
    }
    public function render()
    {
        return view($this->getView(), [
            'button' => $this,
            'manager' => $this->getManager(),
            'item' => $this->getData(),
        ])->render();
    }
}
