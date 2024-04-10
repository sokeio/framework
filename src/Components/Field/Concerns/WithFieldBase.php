<?php

namespace Sokeio\Components\Field\Concerns;

use Sokeio\Components\Common\Concerns\WithName;
use Sokeio\Components\UI;

trait WithFieldBase
{
    use WithName;
    public static function getFieldName($fieldEncode)
    {
        return str_replace(UI::KEY_FIELD_NAME, '.', $fieldEncode);
    }

    public function getNameEncode()
    {
        return self::getFieldName($this->getName());
    }
    public function placeholder($placeholder): static
    {
        return $this->setKeyValue('placeholder', $placeholder);
    }
    public function getPlaceholder()
    {
        return $this->getValue('placeholder');
    }
    public function valueDefault($valueDefault): static
    {
        return $this->setKeyValue('valueDefault', $valueDefault);
    }
    public function getValueDefault()
    {
        return $this->getValue('valueDefault');
    }
    public function noSave(): static
    {
        return $this->setKeyValue('noSave', true);
    }
    public function getNoSave()
    {
        return $this->getValue('noSave');
    }
    public function format($format): static
    {
        return $this->setKeyValue('format', $format);
    }
    public function getFormat()
    {
        return $this->getValue('format');
    }
    public function afterTemplate($afterTemplate): static
    {
        return $this->setKeyValue('afterTemplate', $afterTemplate);
    }
    public function getAfterTemplate()
    {
        return $this->getValue('afterTemplate');
    }

    public function getFormFieldEncode()
    {
        return self::getFieldName($this->getFormField());
    }
    public function getFormField()
    {
        if ($this->checkPrex()) {
            $field = $this->getPrex() . '.';

            $operator = $this->getOperatorField();
            if ($operator != '') {
                $field .=  ($operator . '.');
            }
            $field .= str_replace('.', UI::KEY_FIELD_NAME, $this->getName());

            return $field;
        }
        return $this->getName();
    }

    public function attributeInput($attributeInput): static
    {
        return $this->setKeyValue('attributeInput', $attributeInput);
    }
    public function getAttributeInput()
    {
        return $this->getValue('attributeInput');
    }
    public function classInput($classInput): static
    {
        return $this->setKeyValue('classInput', $classInput);
    }
    public function getClassInput()
    {
        return $this->getValue('classInput');
    }
    public function attributeLabel($attributeLabel): static
    {
        return $this->setKeyValue('attributeLabel', $attributeLabel);
    }
    public function getAttributeLabel()
    {
        return $this->getValue('attributeLabel');
    }
    public function disable($disable = true): static
    {
        return $this->setKeyValue('disable', $disable);
    }
    public function getDisable()
    {
        return $this->getValue('disable');
    }
    public function infoText($infoText): static
    {
        return $this->setKeyValue('infoText', $infoText);
    }
    public function getInfoText()
    {
        return $this->getValue('InfoText');
    }
    public function beforeUI($beforeUI): static
    {
        return $this->setKeyValue('beforeUI', $beforeUI);
    }
    public function getUIBefore()
    {
        return $this->getValue('beforeUI');
    }
    public function afterUI($afterUI): static
    {
        return $this->setKeyValue('afterUI', $afterUI);
    }
    public function getUIAfter()
    {
        return $this->getValue('afterUI');
    }
}
