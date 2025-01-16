<?php

namespace Sokeio;

use Sokeio\Core\ItemInfo;

interface ILoader
{
    public static function runLoad(ItemInfo $itemInfo);
}
