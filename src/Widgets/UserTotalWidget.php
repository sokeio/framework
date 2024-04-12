<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\User;

class UserTotalWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->Name(__('User Total'))->WidgetNumber()->Data(function () {
            return [
                'widgetTitle' => __('User Total'),
                'widgetData' => User::count()
            ];
        });
    }
}
