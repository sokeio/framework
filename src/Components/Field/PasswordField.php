<?php

namespace Sokeio\Components\Field;


class PasswordField extends BaseField
{
    public function getFieldView()
    {
        return 'sokeio::components.field.password';
    }
    public function hideIconShowPassword($hideIconShowPassword = true): static
    {
        return $this->setKeyValue('hideIconShowPassword', $hideIconShowPassword);
    }
    public function getHideIconShowPassword()
    {
        return $this->getValue('hideIconShowPassword', false);
    }
}
