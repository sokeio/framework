<?php

namespace Sokeio\Components\Common;


class Column extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
        $this->col('col');
    }
    public function col($col)
    {
        return $this->setKeyValue('col', $col);
    }
    public function getCol()
    {
        return $this->getValue('col');
    }
    public function col1()
    {
        return $this->col('col1');
    }
    public function col2()
    {
        return $this->col('col2');
    }
    public function col3()
    {
        return $this->col('col3');
    }
    public function col4()
    {
        return $this->col('col4');
    }
    public function col5()
    {
        return $this->col('col5');
    }
    public function col6()
    {
        return $this->col('col6');
    }
    public function col7()
    {
        return $this->col('col7');
    }
    public function col8()
    {
        return $this->col('col8');
    }
    public function col9()
    {
        return $this->col('col9');
    }
    public function col10()
    {
        return $this->col('col10');
    }
    public function col11()
    {
        return $this->col('col11');
    }
    public function col12()
    {
        return $this->col('col12');
    }
    public function colAuto()
    {
        return $this->col('auto');
    }
    public function getView()
    {
        return 'sokeio::components.common.column';
    }
}
