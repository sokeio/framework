<?php

namespace Sokeio;

use Sokeio\Platform\ItemInfo;

interface ILoader
{
    public static function runLoad(ItemInfo $itemInfo);
}
