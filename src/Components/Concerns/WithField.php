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
use Sokeio\Components\Field\TemplateField;
use Sokeio\Components\Field\TextareaField;
use Sokeio\Components\Field\TextField;
use Sokeio\Components\Field\TinymceField;
use Sokeio\Components\Field\TreeViewField;

trait WithField
{
    public static function text($value)
    {
        return TextField::make($value);
    }
    public static function checkBox($value)
    {
        return CheckboxField::make($value);
    }
    public static function checkBoxMutil($value)
    {
        return CheckboxMutilField::make($value);
    }
    public static function toggle($value)
    {
        return ToggleField::make($value);
    }
    public static function toggleMutil($value)
    {
        return ToggleMutilField::make($value);
    }
    public static function radio($value)
    {
        return RadioField::make($value);
    }
    public static function radioMutil($value)
    {
        return RadioMutilField::make($value);
    }
    public static function password($value)
    {
        return PasswordField::make($value);
    }
    public static function select($value)
    {
        return SelectField::make($value);
    }
    public static function textarea($value)
    {
        return TextareaField::make($value);
    }
    public static function range($value)
    {
        return RangeField::make($value);
    }
    public static function tinymce($value)
    {
        return TinymceField::make($value);
    }
    public static function tagify($value)
    {
        return TagifyField::make($value);
    }
    public static function datePicker($value)
    {
        return DatePickerField::make($value);
    }
    public static function number($value)
    {
        return NumberField::make($value);
    }
    public static function image($value)
    {
        return ImageField::make($value);
    }
    public static function chooseModal($value)
    {
        return ModalField::make($value);
    }
    public static function hidden($value)
    {
        return HiddenField::make($value);
    }
    public static function icon($value)
    {
        return IconField::make($value);
    }
    public static function color($value)
    {
        return ColorField::make($value);
    }
    public static function selectWithSearch($value)
    {
        return SelectWithSearchField::make($value);
    }
    public static function treeView($value)
    {
        return TreeViewField::make($value);
    }
    public static function templateField($value)
    {
        return TemplateField::make($value);
    }
}
