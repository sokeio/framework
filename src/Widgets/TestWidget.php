<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\Role;

class TestWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->name(__('Test'))->view('sokeio::test')->data(function () {
            return [
                'widgetTitle' => __('Role Total'),
                'widgetData' => Role::count()
            ];
        })->action('test', function ($pam) {
            $this->getComponent()->showMessage(json_encode($pam));
        });
    }
}
