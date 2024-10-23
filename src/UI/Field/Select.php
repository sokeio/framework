<?php

namespace Sokeio\UI\Field;


class Select extends FieldUI
{
    private $datasource;
    public function dataSource($datasource)
    {
        $this->datasource = $datasource;
        return $this;
    }
    public function remoteAction($action, $name = null)
    {
        return $this->register(function () use ($action, $name) {
            if (!$name) {
                $name = $this->getVar('name', null, true) . '_remote';
            }
            $this->attr('wire:tom-select.remote-action', $name);
            $this->action($name, $action);
            $this->attr('wire:ignore', null, 'wrapper');
        });
    }
    public function multiple()
    {
        return $this->attr('multiple');
    }
    public function view()
    {
        $attr = $this->getAttr();
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        if ($label = $this->getVar('label', '', true)) {
            return <<<HTML
            <div {$attrWrapper}>
                <label class="form-label">{$label}</label>
                <select {$attr} ></select>
            </div>
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper}>
        <select {$attr} ></select>
        </div>
        HTML;
    }
}
