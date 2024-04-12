<?php

namespace Sokeio\Widgets;

use Sokeio\Dashboard\Widget;
use Sokeio\Models\Role;

class TestWidget extends Widget
{
    public function __construct($key)
    {
        parent::__construct($key);
        $this->Name(__('Test'))->View('sokeio::test')->Data(function () {
            return [
                'widgetTitle' => __('Role Total'),
                'widgetData' => Role::count()
            ];
        })->Action('test', function ($pam) {
            $this->getComponent()->showMessage(json_encode($pam));
            return;
        });
    }
}
