<?php

namespace Sokeio\Components\Common;


class Column extends BaseCommon
{
    protected function __construct($value)
    {
        parent::__construct($value);
        $this->Col('col');
    }
    public function Col($Col)
    {
        return $this->setKeyValue('Col', $Col);
    }
    public function getCol()
    {
        return $this->getValue('Col');
    }
    public function Col1()
    {
        return $this->Col('col1');
    }
    public function Col2()
    {
        return $this->Col('col2');
    }
    public function Col3()
    {
        return $this->Col('col3');
    }
    public function Col4()
    {
        return $this->Col('col4');
    }
    public function Col5()
    {
        return $this->Col('col5');
    }
    public function Col6()
    {
        return $this->Col('col6');
    }
    public function Col7()
    {
        return $this->Col('col7');
    }
    public function Col8()
    {
        return $this->Col('col8');
    }
    public function Col9()
    {
        return $this->Col('col9');
    }
    public function Col10()
    {
        return $this->Col('col10');
    }
    public function Col11()
    {
        return $this->Col('col11');
    }
    public function Col12()
    {
        return $this->Col('col12');
    }
    public function ColAuto()
    {
        return $this->Col('auto');
    }
    public function getView()
    {
        return 'sokeio::components.common.column';
    }
}
