<?php

namespace Sokeio\Widgets;

use Sokeio\Components\UI;
use Sokeio\Dashboard\Widget;
use Sokeio\Facades\Platform;

class ModelCountWidget extends Widget
{
    public static function getId()
    {
        return 'sokeio::widgets.model-count';
    }
    public static function getScreenshot()
    {
        return '
        <i class="ti ti-numbers fs-2"></i>
        ';
    }
    public static function getTitle()
    {
        return __('Model Count');
    }
    protected static function param()
    {
        return [
            UI::select('model')->label(__('Model'))->dataSource(function () {
                return [
                    [
                        'id' => '',
                        'title' => __('Choose Model')
                    ],
                    ...collect(Platform::getModels())
                        ->map(function ($item, $key) {
                            return [
                                'id' => $key,
                                'title' => $item
                            ];
                        })->toArray()
                ];
            })
        ];
    }
    public function boot()
    {
        $this->view(self::WIDGET_NUMBER);
        return  parent::boot();
    }
    public function getData()
    {
        $model = $this->getOptionByKey('model');
        if ($model) {
            $model = app($model);
        }
        return [
            'number' => $model?->count() ?? 0,
            'title' => $this->getOptionByKey('title'),
            'icon' => $this->getOptionByKey('icon'),
        ];
    }
}
