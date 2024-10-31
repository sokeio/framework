<?php

namespace Sokeio\Widget;

use Sokeio\Platform;
use Sokeio\Support\Widget\WidgetInfo;
use Sokeio\Support\Widget\WidgetUI;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Select;


#[WidgetInfo('sokeio:count-model', 'Count Of Model', 'ti ti-bar-chart-2')]
class CountModelWidget extends WidgetUI
{
    public static function paramUI()
    {
        return [
            Input::init('title')->label(__('Title')),
            Select::init('model')->label(__('Model'))->dataSource(function () {
                return collect(Platform::getAllModel())->map(function ($item, $key) {
                    return [
                        'value' => $key,
                        'text' => $item['name']
                    ];
                });
            }),
        ];
    }
    public function view()
    {
        $value = 0;
        $model = $this->getDataParam('model');
        if ($model) {
            $model = Platform::getModelByKey($model, 'class');
        }
        if ($model && class_exists($model)) {
            $value = $model::query()->count();
        }

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
                        {$value}
                        </div>
                    </div>
                </div>
        HTML;
    }
}
