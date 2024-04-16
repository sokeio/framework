<?php

namespace Sokeio\Components\Common\Concerns;

trait WithColumnGrid
{
    public function sm($sm)
    {
        return $this->setKeyValue('sm', $sm);
    }
    public function getSm()
    {
        return $this->getValue('sm');
    }
    public function md($md)
    {
        return $this->setKeyValue('md', $md);
    }
    public function getMd()
    {
        return $this->getValue('md');
    }
    public function lg($lg)
    {
        return $this->setKeyValue('lg', $lg);
    }
    public function getLg()
    {
        return $this->getValue('lg');
    }
    public function xl($xl)
    {
        return $this->setKeyValue('xl', $xl);
    }
    public function getXl()
    {
        return $this->getValue('xl');
    }
    public function xxl($xxl)
    {
        return $this->setKeyValue('xxl', $xxl);
    }
    public function getXxl()
    {
        return $this->getValue('xxl');
    }
    public function getColumnClass()
    {
        $sm = $this->getSm() ?? -1;
        $md = $this->getMd() ?? -1;
        $lg = $this->getLg() ?? -1;
        $xl = $this->getXl() ?? -1;
        $xxl = $this->getXxl() ?? -1;
        if ($sm == 0 && $md == 0 && $lg == 0 && $xl == 0 && $xxl == 0) {
            return ' col ';
        }
        $class = "";
        if ($sm > 0) {
            $class .= "col-sm-{$sm} ";
        } else {
            $class .= "col-sm-12 ";
        }
        if ($md > 0) {
            $class .= "col-md-{$md} ";
        } else {
            $class .= "col-md-12 ";
        }
        if ($lg > 0) {
            $class .= "col-lg-{$lg} ";
        } else {
            $class .= "col-lg-12 ";
        }
        if ($xl > 0) {
            $class .= "col-xl-{$xl} ";
        } else {
            $class .= "col-xl-12 ";
        }
        if ($xxl > 0) {
            $class .= "col-xxl-{$xxl} ";
        } else {
            $class .= "col-xxl-12 ";
        }
        return $class;
    }

    public function col()
    {
        return $this->sm(0)->md(0)->lg(0)->xl(0)->xxl(0);
    }
    public function col1()
    {
        return $this->sm(12)->md(12)->lg(1)->xl(1)->xxl(1);
    }
    public function col2()
    {
        return $this->sm(12)->md(12)->lg(2)->xl(2)->xxl(2);
    }
    public function col3()
    {
        return $this->sm(12)->md(12)->lg(3)->xl(3)->xxl(3);
    }
    public function col4()
    {
        return $this->sm(12)->md(12)->lg(4)->xl(4)->xxl(4);
    }
    public function col5()
    {
        return $this->sm(12)->md(12)->lg(5)->xl(5)->xxl(5);
    }
    public function col6()
    {
        return $this->sm(12)->md(12)->lg(6)->xl(6)->xxl(6);
    }
    public function col7()
    {
        return $this->sm(12)->md(12)->lg(7)->xl(7)->xxl(7);
    }
    public function col8()
    {
        return $this->sm(12)->md(12)->lg(8)->xl(8)->xxl(8);
    }
    public function col9()
    {
        return $this->sm(12)->md(12)->lg(9)->xl(9)->xxl(9);
    }
    public function col10()
    {
        return $this->sm(12)->md(12)->lg(10)->xl(10)->xxl(10);
    }
    public function col11()
    {
        return $this->sm(12)->md(12)->lg(11)->xl(11)->xxl(11);
    }

    public function col12()
    {
        return $this->sm(12)->md(12)->lg(12)->xl(12)->xxl(12);
    }
    public function colAuto()
    {
        return $this->sm('auto')->md('auto')->lg('auto')->xl('auto')->xxl('auto');
    }
}
