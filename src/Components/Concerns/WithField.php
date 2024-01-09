<?php

namespace Sokeio\Admin\Components\Concerns;

use Sokeio\Admin\Components\Field\CheckboxField;
use Sokeio\Admin\Components\Field\CheckboxMutilField;
use Sokeio\Admin\Components\Field\DatePickerField;
use Sokeio\Admin\Components\Field\ImageField;
use Sokeio\Admin\Components\Field\ModalField;
use Sokeio\Admin\Components\Field\NumberField;
use Sokeio\Admin\Components\Field\ToggleField;
use Sokeio\Admin\Components\Field\ToggleMutilField;
use Sokeio\Admin\Components\Field\RadioField;
use Sokeio\Admin\Components\Field\RadioMutilField;
use Sokeio\Admin\Components\Field\PasswordField;
use Sokeio\Admin\Components\Field\RangeField;
use Sokeio\Admin\Components\Field\SelectField;
use Sokeio\Admin\Components\Field\TagifyField;
use Sokeio\Admin\Components\Field\TextareaField;
use Sokeio\Admin\Components\Field\TextField;
use Sokeio\Admin\Components\Field\TinymceField;

trait WithField
{
    public static function Text($value)
    {
        return TextField::make($value);
    }
    public static function Checkbox($value)
    {
        return CheckboxField::make($value);
    }
    public static function CheckboxMutil($value)
    {
        return CheckboxMutilField::make($value);
    }
    public static function Toggle($value)
    {
        return ToggleField::make($value);
    }
    public static function ToggleMutil($value)
    {
        return ToggleMutilField::make($value);
    }
    public static function Radio($value)
    {
        return RadioField::make($value);
    }
    public static function RadioMutil($value)
    {
        return RadioMutilField::make($value);
    }
    public static function Password($value)
    {
        return PasswordField::make($value);
    }
    public static function Select($value)
    {
        return SelectField::make($value);
    }
    public static function Textarea($value)
    {
        return TextareaField::make($value);
    }
    public static function Range($value)
    {
        return RangeField::make($value);
    }
    public static function Tinymce($value)
    {
        return TinymceField::make($value);
    }
    public static function Tagify($value)
    {
        return TagifyField::make($value);
    }
    public static function DatePicker($value)
    {
        return DatePickerField::make($value);
    }
    public static function Number($value)
    {
        return NumberField::make($value);
    }
    public static function Image($value)
    {
        return ImageField::make($value);
    }
    public static function ChooseModal($value)
    {
        return ModalField::make($value);
    }
}
