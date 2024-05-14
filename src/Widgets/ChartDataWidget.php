<?php

namespace Sokeio\Widgets;

use Sokeio\Components\UI;
use Sokeio\Dashboard\Widget;
use Sokeio\Facades\Platform;

class ChartDataWidget extends Widget
{
    public static function getId()
    {
        return 'sokeio::widgets.chart-demo-data';
    }
    public static function getScreenshot()
    {
        return '
        <i class="ti ti-bar-chart fs-2"></i>
        ';
    }
    public static function getTitle()
    {
        return __('Chart Demo Data');
    }
    protected static function param()
    {
        return [];
    }
    public function boot()
    {
        $this->view(self::WIDGET_APEXCHARTS);
        return  parent::boot();
    }
    public function getData()
    {
        
        return [
            'title' => $this->getOptionByKey('title'),
            'icon' => $this->getOptionByKey('icon'),
        ];
    }
}
