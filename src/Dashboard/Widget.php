<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Components\UI;
use Sokeio\Facades\Dashboard;
use Sokeio\Laravel\BaseCallback;

class Widget extends BaseCallback
{
    use Macroable;
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
            UI::select('position')->label(__('position'))->options(function () {
                return Dashboard::getPosition();
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
    public function data($data): static
    {
        return $this->setKeyValue('data', $data);
    }
    public function getData()
    {
        return $this->getValue('data');
    }
    public function widgetNumber()
    {
        return $this->view('sokeio::widgets.number-widget');
    }
    public function widgetApexcharts()
    {
        return $this->view('sokeio::widgets.apexcharts-widget');
    }
}
