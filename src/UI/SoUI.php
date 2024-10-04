<?php

namespace Sokeio\UI;

use PhpParser\Node\Expr\FuncCall;

class SoUI
{
    private $ui = [];
    private $actions = [];
    public function action($name, $callback, $ui = null)
    {
        $this->actions[$name] = [
            'callback' => $callback,
            'ui' => $ui
        ];
    }
    public function __construct($ui = [])
    {
        if (!$ui) {
            return;
        }
        if (!is_array($ui)) {
            $ui = [$ui];
        }
        $this->ui = $ui;
    }
    public function render()
    {
        //register
        foreach ($this->ui as $ui) {
            $ui->setManager($this);
            $ui->runRegister();
        }
        //boot
        foreach ($this->ui as $ui) {
            $ui->runBoot();
        }
        //ready
        foreach ($this->ui as $ui) {
            $ui->runReady();
        }
        //render
        $html = '';
        foreach ($this->ui as $ui) {
            $html .= $ui->runRender();
        }
        return $html;
    }
}
