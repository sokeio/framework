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
use Sokeio\Components\Common\Template;

trait WithCommon
{
    public static function tab()
    {
        return Tab::make('');
    }
    public static function card($value)
    {
        return Card::make($value);
    }
    public static function div($value)
    {
        return Div::make($value);
    }
    public static function forEach($arrayData, $value)
    {
        return Each::make($value)->arrayData($arrayData);
    }
    public static function container($value)
    {
        return Container::make($value);
    }
    public static function prex($prex, $value)
    {
        return Container::make($value)->prex($prex);
    }
    public static function button($value)
    {
        return Button::make($value);
    }
    public static function buttonCreate($value)
    {
        return Button::make($value)->green();
    }
    public static function buttonEdit($value)
    {
        return Button::make($value)->secondary();
    }
    public static function buttonRemove($value)
    {
        return Button::make($value)->warning();
    }
    public static function buttonList($value)
    {
        return ButtonList::make($value);
    }
    public static function buttonGroup($value)
    {
        return ButtonGroup::make($value);
    }
    public static function livewire($value, $params = null)
    {
        return Livewire::make($value)->params($params);
    }

    public static function error($value)
    {
        return Error::make($value);
    }
    public static function template($value)
    {
        return Template::make($value);
    }
}
