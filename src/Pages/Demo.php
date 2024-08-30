<?php

namespace Sokeio\Pages;


class Demo extends \Sokeio\PageApi
{
    public function action()
    {
        return static::Json('Hello World');
    }
}
