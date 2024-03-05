<?php

namespace Sokeio\Components\Field;


class PasswordField extends BaseField
{
    public function getFieldView()
    {
        return 'sokeio::components.field.password';
    }
    public function HideIconShowPassword($HideIconShowPassword = true): static
    {
        return $this->setKeyValue('HideIconShowPassword', $HideIconShowPassword);
    }
    public function getHideIconShowPassword()
    {
        return $this->getValue('HideIconShowPassword', false);
    }
}
