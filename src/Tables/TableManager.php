<?php

namespace BytePlatform\Tables;

use BytePlatform\ItemManager;

class TableManager
{
    public static function Render(ItemManager $itemManager, $dataItems = null, $dataFilters = null, $dataSorts = null, $formTable = null, $selectIds = null)
    {
        return view('byte::tables.render', [
            'manager' => $itemManager,
            'items' => $dataItems,
            'filters' => $dataFilters,
            'sorts' => $dataSorts,
            'formTable' => $formTable,
            'selectIds' => $selectIds,
        ])->render();
    }
}
