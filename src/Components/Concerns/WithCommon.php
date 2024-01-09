<?php

namespace Sokeio\Admin\Components\Concerns;

use Sokeio\Admin\Components\Common\Button;
use Sokeio\Admin\Components\Common\ButtonGroup;
use Sokeio\Admin\Components\Common\ButtonList;
use Sokeio\Admin\Components\Common\Card;
use Sokeio\Admin\Components\Common\Container;
use Sokeio\Admin\Components\Common\Div;
use Sokeio\Admin\Components\Common\Tab;

trait WithCommon
{
    public static function Tab()
    {
        return Tab::make('');
    }
    public static function Card($value)
    {
        return Card::make($value);
    }
    public static function Div($value)
    {
        return Div::make($value);
    }
    public static function Container($value)
    {
        return Container::make($value);
    }
    public static function Prex($prex, $value)
    {
        return Container::make($value)->Prex($prex);
    }
    public static function Button($value)
    {
        return Button::Make($value);
    }
    public static function ButtonCreate($value)
    {
        return Button::Make($value)->Green();
    }
    public static function ButtonEdit($value)
    {
        return Button::Make($value)->Secondary();
    }
    public static function ButtonRemove($value)
    {
        return Button::Make($value)->Warning();
    }
    public static function ButtonList($value)
    {
        return ButtonList::Make($value);
    }
    public static function ButtonGroup($value)
    {
        return ButtonGroup::Make($value);
    }
}
