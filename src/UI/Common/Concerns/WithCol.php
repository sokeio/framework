<?php

namespace Sokeio\UI\Common\Concerns;

trait WithCol
{
    protected function classNameCol($class)
    {
        return $this->attrAdd('class', $class);
    }
    public function col($xxl = 12, $xl = 12, $lg = 12, $md = 12, $sm = 12): static
    {
        return $this->classNameCol('col-sm-' . $sm)
            ->classNameCol('col-md-' . $md)
            ->classNameCol('col-lg-' . $lg)
            ->classNameCol('col-xl-' . $xl)
            ->classNameCol('col-xxl-' . $xxl);
    }
    public function colNone()
    {
        return $this->classNameCol('col-sm')
            ->classNameCol('col-md')
            ->classNameCol('col-lg')
            ->classNameCol('col-xl')
            ->classNameCol('col-xxl');
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
        return $this->col(7, 6, 12, 12);
    }
    public function col8()
    {
        return $this->col(8, 6, 12, 12);
    }
    public function col9()
    {
        return $this->col(9, 12, 12, 12);
    }
    public function col10()
    {
        return $this->col(10, 12, 12, 12);
    }
    public function col11()
    {
        return $this->col(11, 12, 12, 12);
    }
    public function col12()
    {
        return $this->col(12, 12, 12, 12);
    }
}
