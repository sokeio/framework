<?php

namespace Sokeio\Dashboard;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WidgetInfo
{
    use WithAttribute;
    public function __construct(
        public $dasboard = 'default',
        public $position = 'default',
        public $show = true,
    ) {}
    public const COLUMN1 = 'column1';
    public const COLUMN2 = 'column2';
    public const COLUMN3 = 'column3';
    public const COLUMN4 = 'column4';
    public const COLUMN5 = 'column5';
    public const COLUMN6 = 'column6';
    public const COLUMN7 = 'column7';
    public const COLUMN8 = 'column8';
    public const COLUMN9 = 'column9';
    public const COLUMN10 = 'column10';
    public const COLUMN11 = 'column11';
    public const COLUMN12 = 'column12';
    public static function getColumns()
    {
        return [
            'column1' => 'col-lg-1 col-md-2 col-sm-6 col-xs-12',
            'column2' => 'col-lg-2 col-md-3 col-sm-6 col-xs-12',
            'column3' => 'col-lg-3 col-md-4 col-sm-6 col-xs-12',
            'column4' => 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
            'column5' => 'col-lg-5 col-md-6 col-sm-12 col-xs-12',
            'column6' => 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
            'column7' => 'col-lg-7 col-md-6 col-sm-12 col-xs-12',
            'column8' => 'col-lg-8 col-md-6 col-sm-12 col-xs-12',
            'column9' => 'col-lg-9 col-md-6 col-sm-12 col-xs-12',
            'column10' => 'col-lg-10 col-md-6 col-sm-12 col-xs-12',
            'column11' => 'col-lg-11 col-md-6 col-sm-12 col-xs-12',
            'column12' => 'col-lg-12 col-md-6 col-sm-12 col-xs-12',
        ];
    }
    public static function getColumn($column)
    {
        return self::getColumns()[$column] ?? 'col-12 col-sm-12 col-xs-12';
    }
}
