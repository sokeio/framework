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
    protected static function paramDefault()
    {
        return [
            UI::text('title')->label(__('title')),
            UI::select('column')->label(__('column'))->options(function () {
                return collect(range(1, 12))->mapWithKeys(function ($value) {
                    return [
                        'id' => 'col' . $value,
                        'name' => 'col ' . $value
                    ];
                });
            }),

            UI::select('poll')->label(__('Poll'))->options(function () {
                return [
                    [
                        'id' => '',
                        'name' => 'No Poll'
                    ],
                    [
                        'id' => '1s',
                        'name' => '1 Second'
                    ],
                    [
                        'id' => '5s',
                        'name' => '5 Seconds'
                    ],
                    [
                        'id' => '10s',
                        'name' => '10 Seconds'
                    ],
                    [
                        'id' => '30s',
                        'name' => '30 Seconds'
                    ],
                    [
                        'id' => '1m',
                        'name' => '1 Minute'
                    ],
                    [
                        'id' => '5m',
                        'name' => '5 Minutes'
                    ],
                    [
                        'id' => '10m',
                        'name' => '10 Minutes'
                    ],
                    [
                        'id' => '30m',
                        'name' => '30 Minutes'
                    ],
                    [
                        'id' => '1h',
                        'name' => '1 Hour'
                    ]
                ];
            }),
            UI::select('ratio')->label(__('ratio'))->options(function () {
                return [
                    [
                        'id' => '',
                        'name' => 'No Ratio'
                    ],
                    [
                        'id' => '1x1',
                        'name' => '1x1'
                    ],
                    [
                        'id' => '2x1',
                        'name' => '2x1'
                    ],
                    [
                        'id' => '4x3',
                        'name' => '4x3'
                    ],
                    [
                        'id' => '16x9',
                        'name' => '16x9'

                    ],
                    [
                        'id' => '21x9',
                        'name' => '21x9'
                    ]
                ];
            })
        ];
    }
    public static function getParams()
    {
        return [...self::paramDefault(), ...self::param() ?? []];
    }
    protected static function param()
    {
        return [];
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
