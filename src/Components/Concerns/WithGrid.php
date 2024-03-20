<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Components\Common\Column;
use Sokeio\Components\Common\Row;

trait WithGrid
{
    public static function row($value)
    {
        return Row::make($value);
    }
    public static function column($value)
    {
        return Column::make($value);
    }
    public static function column1($value)
    {
        return Column::make($value)->col1();
    }
    public static function column2($value)
    {
        return Column::make($value)->col2();
    }
    public static function column3($value)
    {
        return Column::make($value)->col3();
    }
    public static function column4($value)
    {
        return Column::make($value)->col4();
    }
    public static function column5($value)
    {
        return Column::make($value)->col5();
    }
    public static function column6($value)
    {
        return Column::make($value)->col6();
    }
    public static function column7($value)
    {
        return Column::make($value)->col7();
    }
    public static function column8($value)
    {
        return Column::make($value)->col8();
    }
    public static function column9($value)
    {
        return Column::make($value)->col9();
    }
    public static function column10($value)
    {
        return Column::make($value)->col10();
    }
    public static function column11($value)
    {
        return Column::make($value)->col11();
    }
    public static function column12($value)
    {
        return Column::make($value)->col12();
    }
    public static function columnAuto($value)
    {
        return Column::make($value)->colAuto();
    }
}
