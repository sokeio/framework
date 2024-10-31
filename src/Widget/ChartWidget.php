<?php

namespace Sokeio\Widget;

use Sokeio\Support\Widget\WidgetInfo;
use Sokeio\Support\Widget\WidgetUI;
use Sokeio\UI\Field\Input;

#[WidgetInfo('sokeio:chart', 'Chart', 'ti ti-bar-chart-2')]
class ChartWidget extends WidgetUI
{
    public static function paramUI()
    {
        return [
            Input::init('title')->label(__('Title')),
        ];
    }
    public function view()
    {
        return <<<HTML
            <div class="row align-items-center p-1">
                <div class="col-auto">
                    <span class="bg-primary text-white avatar">
                        <i class="ti ti-currency-dollar fs-2"></i>
                    </span>
                </div>
                <div class="col">
                    <div class="font-weight-medium">
                    {$this->getDataParam('title', '')}
                    </div>
                    <div class="text-secondary ">
                    </div>
                </div>
            </div>
        HTML;
    }
}
