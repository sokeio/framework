<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Log;

class SoUI
{
    private $ui = [];
    private $actions = [];
    private $wire = null;
    public function getWire()
    {
        return $this->wire;
    }
    public function action($name, $callback, $ui = null)
    {
        $this->actions[$name] = [
            'callback' => $callback,
            'ui' => $ui
        ];
    }
    public function callAction($name, $params = [])
    {
        $action = $this->actions[$name];
        if ($action['callback']) {
            call_user_func($action['callback'], $params);
        }
    }
    public function __construct($ui = [], $wire = null)
    {
        $this->wire = $wire;
        if (!$ui) {
            return;
        }
        if (!is_array($ui)) {
            $ui = [$ui];
        }
        $this->ui = $ui;
        $this->register();
    }
    public function register()
    {
        Log::info('UI: register');
        //register
        foreach ($this->ui as $ui) {
            $ui->setManager($this);
            $ui->runRegister();
        }
    }
    public function boot()
    {
        Log::info('UI: boot');
        //boot
        foreach ($this->ui as $ui) {
            $ui->setManager($this);
            $ui->runBoot();
        }
        Log::info('UI: ready');
        //ready
        foreach ($this->ui as $ui) {
            $ui->runReady();
        }
    }
    public function render()
    {
        Log::info('UI: render');
        //render
        $html = '';
        foreach ($this->ui as $ui) {
            $html .= $ui->runRender();
        }
        return $html;
    }
    public function toArray()
    {
        $ui = [];
        foreach ($this->ui as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $ui[$key][$k] = $v->toArray();
                }
            } else {
                $ui[$key] = $value->toArray();
            }
        }
        return $ui;
    }
    public function toUI($arr)
    {
        return BaseUI::toUI($arr);
    }
    public static function init($ui, $wire = null)
    {
        return new SoUI($ui, $wire);
    }
    public static function renderUI($ui, $wire = null)
    {
        return static::init($ui, $wire)->render();
    }
}
