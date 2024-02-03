<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Components\Field\CheckboxField;
use Sokeio\Components\Field\CheckboxMutilField;
use Sokeio\Components\Field\ColorField;
use Sokeio\Components\Field\DatePickerField;
use Sokeio\Components\Field\HiddenField;
use Sokeio\Components\Field\IconField;
use Sokeio\Components\Field\ImageField;
use Sokeio\Components\Field\ModalField;
use Sokeio\Components\Field\NumberField;
use Sokeio\Components\Field\ToggleField;
use Sokeio\Components\Field\ToggleMutilField;
use Sokeio\Components\Field\RadioField;
use Sokeio\Components\Field\RadioMutilField;
use Sokeio\Components\Field\PasswordField;
use Sokeio\Components\Field\RangeField;
use Sokeio\Components\Field\SelectField;
use Sokeio\Components\Field\SelectWithSearchField;
use Sokeio\Components\Field\TagifyField;
use Sokeio\Components\Field\TextareaField;
use Sokeio\Components\Field\TextField;
use Sokeio\Components\Field\TinymceField;

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
    public static function Hidden($value)
    {
        return HiddenField::make($value);
    }
    public static function Icon($value)
    {
        return IconField::make($value);
    }
    public static function Color($value)
    {
        return ColorField::make($value);
    }
    public static function SelectWithSearch($value)
    {
        return SelectWithSearchField::make($value);
    }
}
