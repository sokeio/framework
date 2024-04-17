<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\Role;

class RoleTotalWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->name(__('Role Total'))->widgetNumber()->data(function () {
            return [
                'widgetTitle' => __('Role Total'),
                'widgetData' => Role::count()
            ];
        })->column('col4');
    }
}
