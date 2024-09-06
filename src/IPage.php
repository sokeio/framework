<?php

namespace Sokeio;


interface IPage  extends ILoader
{
    public static function pageAuth();
    public static function pageName();
    public static function pageUrl();
    public static function pageIcon();
}
