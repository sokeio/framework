<?php

namespace Sokeio\Enums;

use Sokeio\Attribute\Label;
use Sokeio\Concerns\AttributableEnum;

enum UIKey: string
{
    use AttributableEnum;
    #[Label('Add menu item')]
    case MENU_ADD_ITEM = 'content::menu_add_item';
    #[Label('Menu item type')]
    case MENU_ITEM_TYPE = 'content::menu_item_';
}
