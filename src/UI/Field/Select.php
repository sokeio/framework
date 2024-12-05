<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\Field\Concerns\WithDatasource;

class Select extends FieldUI
{
    use WithDatasource;
    private $options = [];
    public function options($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function createItem()
    {
        return $this->options([
            'create' => true
        ]);
    }
    public function createItemOnBlur()
    {
        return $this->options([
            'createOnBlur' => true
        ]);
    }
    public function initUI()
    {
        parent::initUI();
        $this->render(function () {
            $this->attr('wire:tom-select');
            $this->attr('wire:tom-select.datasource', json_encode($this->getDatasource()));
            if ($this->options) {
                $this->attr('wire:tom-select.options', json_encode($this->options));
            }
        });
    }
    public function remoteActionWithModel(
        $model,
        $filedText = 'name',
        $fillable = null,
        $fieldSearch = null,
        $fieldId = 'id',
        $mapData = null,
        $limit = 30,
        $name = null
    ) {
        return $this->remoteAction(
            fn($value) => $this->getDataByModel(
                $model,
                $filedText,
                $fillable,
                $fieldId,
                $mapData,
                $limit,
                $fieldSearch,
                $value
            ),
            $name
        );
    }
    public function remoteAction($action, $name = null)
    {
        return $this->register(function () use ($action, $name) {
            if (!$name) {
                $name = $this->getVar('name', null, true) . '_' . (class_basename(($this))) . '_remote_action';
            }
            $this->attr('wire:tom-select.remote-action', $name);
            $this->action($name, $action);
            $this->render(function () use ($name) {
                if (empty($this->dataSource)) {
                    $this->dataSource($this->getManager()->callActionUI($name));
                }
            });
        });
    }
    public function multiple()
    {
        return $this->attr('multiple', 'true');
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $value = $this->getValue();
        $optionHtml = '';
        if (is_array($value)) {
            foreach ($value as $item) {
                $optionHtml .= '<option value="' . $item . '" selected></option>';
            }
        } else {
            $optionHtml = '<option value="' . $value . '" selected></option>';
        }
        return <<<HTML
        <div wire:ignore>
            <select {$attr} >
               {$optionHtml}
            </select>
        </div>
        HTML;
    }
}
