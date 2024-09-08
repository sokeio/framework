<?php

namespace Sokeio;

use Sokeio\Support\Platform\ItemInfo;

interface ILoader
{
    public static function runLoad(ItemInfo $itemInfo);
}
