<?php

namespace Sokeio\Pages;


class Demo extends \Sokeio\PageApi
{
    public function action()
    {
        return \Sokeio\PageApi::Json('Hello World');
    }
}
