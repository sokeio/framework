<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Components\Common\Button;
use Sokeio\Components\Common\ButtonGroup;
use Sokeio\Components\Common\ButtonList;
use Sokeio\Components\Common\Card;
use Sokeio\Components\Common\Container;
use Sokeio\Components\Common\Div;
use Sokeio\Components\Common\Each;
use Sokeio\Components\Common\Error;
use Sokeio\Components\Common\Livewire;
use Sokeio\Components\Common\Tab;

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
    public static function ForEach($arrayData, $value)
    {
        return Each::make($value)->ArrayData($arrayData);
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
    public static function Livewire($value, $params = null)
    {
        return Livewire::Make($value)->Params($params);
    }

    public static function Error($value)
    {
        return Error::Make($value);
    }
}
