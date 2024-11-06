<?php

namespace Sokeio\UI\Common\Concerns;

trait DivGrid
{
    public function container($size = '')
    {
        return $this->className('container' . ($size ? '-' . $size : ''));
    }
    public function row()
    {
        return $this->className('row');
    }
    public function col($xxl = 12, $xl = 12, $lg = 12, $md = 12, $sm = 12)
    {
        return $this->className('col-sm-' . $sm)
            ->className('col-md-' . $md)
            ->className('col-lg-' . $lg)
            ->className('col-xl-' . $xl)
            ->className('col-xxl-' . $xxl);
    }
    public function colAuto()
    {
        return $this->col('auto', 'auto', 'auto', 'auto');
    }
    public function col1()
    {
        return $this->col(1, 2, 4, 6, 6);
    }
    public function col2()
    {
        return $this->col(2, 4, 8, 12);
    }
    public function col3()
    {
        return $this->col(3, 6, 8, 12);
    }
    public function col4()
    {
        return $this->col(4, 6, 12, 12);
    }
    public function col5()
    {
        return $this->col(5, 6, 12, 12);
    }
    public function col6()
    {
        return $this->col(6, 6, 12, 12);
    }
    public function col7()
    {
        return $this->col(7, 7, 12, 12);
    }
    public function col8()
    {
        return $this->col(8, 8, 12, 12);
    }
    public function col9()
    {
        return $this->col(9, 9, 12, 12);
    }
    public function col10()
    {
        return $this->col(10, 10, 12, 12);
    }
    public function col11()
    {
        return $this->col(11, 11, 12, 12);
    }
    public function col12()
    {
        return $this->col(12, 12, 12, 12);
    }
}
