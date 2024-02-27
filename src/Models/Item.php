<?php

namespace Sokeio\Models;

use Sokeio\Model;

class Item extends Model
{
    public function group()
    {
        return $this->hasOne(GroupItem::class, 'id', 'group_item_id');
    }
}
