<?php

namespace Sokeio\Admin\Components\Concerns;

use Sokeio\Admin\Components\Common\Column;
use Sokeio\Admin\Components\Common\Row;

trait WithGrid
{
    public static function Row($value)
    {
        return Row::make($value);
    }
    public static function Column($value)
    {
        return Column::make($value);
    }
    public static function Column1($value)
    {
        return Column::make($value)->Col1();
    }
    public static function Column2($value)
    {
        return Column::make($value)->Col2();
    }
    public static function Column3($value)
    {
        return Column::make($value)->Col3();
    }
    public static function Column4($value)
    {
        return Column::make($value)->Col4();
    }
    public static function Column5($value)
    {
        return Column::make($value)->Col5();
    }
    public static function Column6($value)
    {
        return Column::make($value)->Col6();
    }
    public static function Column7($value)
    {
        return Column::make($value)->Col7();
    }
    public static function Column8($value)
    {
        return Column::make($value)->Col8();
    }
    public static function Column9($value)
    {
        return Column::make($value)->Col9();
    }
    public static function Column10($value)
    {
        return Column::make($value)->Col10();
    }
    public static function Column11($value)
    {
        return Column::make($value)->Col11();
    }
    public static function Column12($value)
    {
        return Column::make($value)->Col12();
    }
    public static function ColumnAuto($value)
    {
        return Column::make($value)->ColAuto();
    }
}
