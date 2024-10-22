<?php

namespace Sokeio\UI\Field;

class Tagify extends FieldUI
{
    private $options = [];
    public function options($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    public function whitelistAction($action, $name = null)
    {
        return $this->register(function () use ($action, $name) {
            if (!$name) {
                $name = $this->getVar('name', null, true) . '_whitelist';
            }
            $this->options([
                'whitelistAction' => $name
            ]);
            $this->action($name, $action);
            $this->attr('wire:ignore', null, 'wrapper');
        });
    }

    public function initUI()
    {
        parent::initUI();
        $this->render(function () {
            if (!$this->containsAttr('class', 'form-control')) {
                $this->className('form-control');
            }
            $this->attr('wire:tagify');
            $this->attr('wire:tagify.options', json_encode($this->options));
        });
    }
}
