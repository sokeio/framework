<?php

namespace Sokeio\Widget;

use Sokeio\Support\Widget\WidgetInfo;
use Sokeio\Support\Widget\WidgetUI;
use Sokeio\UI\Field\Input;

#[WidgetInfo('sokeio:count-model', 'Count Of Model', 'ti ti-bar-chart-2')]
class CountModelWidget extends WidgetUI
{
    public static function paramUI()
    {
        return [
            Input::init('title')->label(__('Title')),
            Input::init('model')->label(__('Model')),
        ];
    }
    public function view()
    {
        return <<<HTML
        <div>
            <div class="text-center">
                <h1 class="display-1">{$this->getDataParam('title',)}</h1>
        </div>
        </div>
        HTML;
    }
}