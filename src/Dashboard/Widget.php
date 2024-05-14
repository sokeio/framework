<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Components\UI;
use Sokeio\Laravel\BaseCallback;

class Widget extends BaseCallback
{
    use Macroable;
    public const WIDGET_NUMBER = 'sokeio::widgets.number-widget';
    public const WIDGET_APEXCHARTS = 'sokeio::widgets.apexcharts-widget';
    public static function getId()
    {
        return 'id';
    }
    private static function paramDefault()
    {
        return [
            UI::text('title')->label(__('title')),
            UI::select('column')->label(__('column'))->dataSource(function () {
                return collect(range(1, 12))->map(function ($value) {
                    return [
                        'id' => 'col' . $value,
                        'title' => 'Col ' . $value
                    ];
                })->toArray();
            })->col4(),


            UI::select('ratio')->label(__('ratio'))->dataSource(function () {
                return [
                    [
                        'id' => '',
                        'title' => 'No Ratio'
                    ],
                    [
                        'id' => '1x1',
                        'title' => '1x1'
                    ],
                    [
                        'id' => '2x1',
                        'title' => '2x1'
                    ],
                    [
                        'id' => '4x3',
                        'title' => '4x3'
                    ],
                    [
                        'id' => '16x9',
                        'title' => '16x9'

                    ],
                    [
                        'id' => '21x9',
                        'title' => '21x9'
                    ]
                ];
            })->col4(),
            UI::select('poll')->label(__('Poll'))->dataSource(function () {
                return [
                    [
                        'id' => '',
                        'title' => 'No Poll'
                    ],
                    [
                        'id' => '1s',
                        'title' => '1 Second'
                    ],
                    [
                        'id' => '5s',
                        'title' => '5 Seconds'
                    ],
                    [
                        'id' => '10s',
                        'title' => '10 Seconds'
                    ],
                    [
                        'id' => '30s',
                        'title' => '30 Seconds'
                    ],
                    [
                        'id' => '1m',
                        'title' => '1 Minute'
                    ],
                    [
                        'id' => '5m',
                        'title' => '5 Minutes'
                    ],
                    [
                        'id' => '10m',
                        'title' => '10 Minutes'
                    ],
                    [
                        'id' => '30m',
                        'title' => '30 Minutes'
                    ],
                    [
                        'id' => '1h',
                        'title' => '1 Hour'
                    ]
                ];
            })->col4(),
        ];
    }
    public static function getParams()
    {
        return [...self::paramDefault(), ...(static::param() ?? [])];
    }
    protected static function param()
    {
        return [];
    }
    public function getParamUI()
    {
        return static::getParams();
    }
    public function boot()
    {
        return $this;
    }

    private $callbackReady = [];
    public function ready($callback)
    {
        if (!is_callable($callback)) {
            return $this;
        }
        $this->callbackReady[] = $callback;
        return $this;
    }
    public function doReady()
    {
        foreach ($this->callbackReady as $callback) {
            call_user_func($callback, $this, $this->getComponent());
        }
    }
    public function action($action, $callback)
    {
        return $this->ready(function ($widget) use ($action, $callback) {
            $widget->getComponent()->addActionUI($action, $callback);
        });
    }
    private $component = null;
    public function component($component): static
    {
        $this->component = $component;
        $this->doReady();
        return $this;
    }
    public function getComponent()
    {
        return $this->component;
    }
    public function view($view): static
    {
        return $this->setKeyValue('view', $view);
    }
    public function getView()
    {
        return $this->getValue('view');
    }
    public function option($option): static
    {
        return $this->setKeyValue('option', $option);
    }
    public function getOption()
    {
        return $this->getValue('option');
    }
    public function getOptionByKey($key, $default = null)
    {
        if (!isset($this->getOption()[$key])) {
            return $default;
        }
        return $this->getOption()[$key];
    }
    public function getData()
    {
        return [];
    }
    public function render()
    {
        return view($this->getView(), $this->getData())->render();
    }
}
