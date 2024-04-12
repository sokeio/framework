<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\Role;

class RoleTotalWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->Name(__('Role Total'))->WidgetNumber()->Data(function () {
            return [
                'widgetTitle' => __('Role Total'),
                'widgetData' => Role::count()
            ];
        })->column('col4');
    }
}
