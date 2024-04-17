<?php

namespace Sokeio\Components\Concerns;


trait WithUtils
{
    public static function convertSortableToItems($data_list, $items, $parent_id)
    {
        foreach ($items as $item) {
            $value = $item['value'];
            $order = $item['order'];
            if (!isset($data_list[$value])) {
                $data_list[$value] = [
                    'id' => $value,
                    'order' => $order,
                    'parent_id' => $parent_id
                ];
            }

            if (isset($item['items']) && count($item['items']) > 0) {
                $_items = $item['items'];
                $data_list = static::convertSortableToItems($data_list, $_items, $value);
            }
        }
        return $data_list;
    }
}
