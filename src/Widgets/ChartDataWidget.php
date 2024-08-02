<?php

namespace Sokeio\Widgets;

use Sokeio\Components\UI;
use Sokeio\Dashboard\Widget;

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
        return [UI::select('chartType')->label(__('Chart Type'))->dataSource(function () {
            return [
                [
                    'id' => 'line',
                    'title' => __('Line')
                ],
                [
                    'id' => 'candlestick',
                    'title' => __('Candlestick')
                ]
            ];
        })];
    }
    public function boot()
    {
        $this->view(self::WIDGET_APEXCHARTS);
        return  parent::boot();
    }
    private function getDataLine()
    {
        // Số lượng điểm dữ liệu
        $dataPointsCount = 10;

        // Khởi tạo mảng dữ liệu
        $dataPoints = [];

        // Tạo số liệu ngẫu nhiên
        for ($i = 0; $i < $dataPointsCount; $i++) {
            $x = $i;
            $y = rand(1, 100); // Số ngẫu nhiên từ 1 đến 100
            $dataPoints[] = ['x' => $x, 'y' => $y];
        }

        // Tạo mảng option cho ApexCharts
        $options = [
            'chart' => [
                'type' => 'line',
            ],
            'series' => [
                [
                    'name' => 'Series 1',
                    'data' => $dataPoints,
                ],
            ],
        ];
        return $options;
    }
    private function getDataCandlestick()
    {
        // Số lượng điểm dữ liệu
        $dataPointsCount = 10;

        // Khởi tạo mảng dữ liệu
        $dataPoints = [];

        // Tạo số liệu ngẫu nhiên
        for ($i = 0; $i < $dataPointsCount; $i++) {
            $x = $i;
            $open = rand(50, 100); // Giá mở cửa ngẫu nhiên từ 50 đến 100
            $high = rand($open, 150); // Giá cao ngẫu nhiên từ giá mở cửa đến 150
            $low = rand(0, $open); // Giá thấp ngẫu nhiên từ 0 đến giá mở cửa
            $close = rand($low, $high); // Giá đóng cửa ngẫu nhiên từ giá thấp đến giá cao
            $dataPoints[] = ['x' => $x, 'y' => [$open, $high, $low, $close]];
        }

        // Tạo mảng option cho ApexCharts
        $options = [
            'chart' => [
                'type' => 'candlestick',
            ],
            'series' => [
                [
                    'name' => 'Series 1',
                    'data' => $dataPoints,
                ],
            ],
        ];

        return $options;
    }
    public function getData()
    {
        $options = $this->getOptionByKey('chartType') === 'line' ? $this->getDataLine() : $this->getDataCandlestick();
        return [
            'title' => $this->getOptionByKey('title'),
            'icon' => $this->getOptionByKey('icon'),
            'chartData' => $options
        ];
    }
}
