<?php

namespace Sokeio\Admin\Components\Field\Concerns;

use Sokeio\Admin\Components\UI;

trait WithFieldBase
{
    public static function getFieldName($fieldEncode)
    {
        return str_replace(UI::KEY_FIELD_NAME, '.', $fieldEncode);
    }
    public function Name($Name): static
    {
        return $this->setKeyValue('Name', $Name);
    }
    public function getName()
    {
        return $this->getValue('Name');
    }
    public function getNameEncode()
    {
        return self::getFieldName($this->getName());
    }
    public function Placeholder($Placeholder): static
    {
        return $this->setKeyValue('Placeholder', $Placeholder);
    }
    public function getPlaceholder()
    {
        return $this->getValue('Placeholder');
    }
    public function NoSave(): static
    {
        return $this->setKeyValue('NoSave', true);
    }
    public function getNoSave()
    {
        return $this->getValue('NoSave');
    }
    public function Format($Format): static
    {
        return $this->setKeyValue('Format', $Format);
    }
    public function getFormat()
    {
        return $this->getValue('Format');
    }
    public function getFormFieldEncode()
    {
        return self::getFieldName($this->getFormField());
    }
    public function getFormField()
    {
        if ($this->checkPrex()) {
            $operator = $this->getOperatorField();
            return $this->getPrex() . '.' . ($operator != '' ?  $operator . '.' : '') . str_replace('.', UI::KEY_FIELD_NAME, $this->getName());
        }
        return $this->getName();
    }

    public function AttributeInput($AttributeInput): static
    {
        return $this->setKeyValue('AttributeInput', $AttributeInput);
    }
    public function getAttributeInput()
    {
        return $this->getValue('AttributeInput');
    }
    public function ClassInput($ClassInput): static
    {
        return $this->setKeyValue('ClassInput', $ClassInput);
    }
    public function getClassInput()
    {
        return $this->getValue('ClassInput');
    }
    public function AttributeLabel($AttributeLabel): static
    {
        return $this->setKeyValue('AttributeLabel', $AttributeLabel);
    }
    public function getAttributeLabel()
    {
        return $this->getValue('AttributeLabel');
    }
    public function Disable($Disable = true): static
    {
        return $this->setKeyValue('Disable', $Disable);
    }
    public function getDisable()
    {
        return $this->getValue('Disable');
    }
}
