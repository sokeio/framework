<?php

namespace Sokeio\Dashboard\Common;

use Sokeio\Dashboard\WidgetUI;

class LabelWidget extends WidgetUI
{
    public function getWidgetLabel()
    {
        return 'demo';
    }
    public function getWidgetValue()
    {
        return 'Hello World';
    }
    public function view()
    {
        $view = <<<HTML
            <div class="so-widget">
                <h3 class="font-weight-medium text-center fw-bold">{$this->getWidgetLabel()}</h3>
                <p class="text-secondary text-center">{$this->getWidgetValue()}</p>
            </div>
        HTML;

        return $view;
    }
}
