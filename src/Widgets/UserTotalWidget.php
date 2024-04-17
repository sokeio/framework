<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\User;

class UserTotalWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->name(__('User Total'))->widgetNumber()->data(function () {
            return [
                'widgetTitle' => __('User Total'),
                'widgetData' => User::count()
            ];
        });
    }
}
