<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\Field\Concerns\WithDatasource;

class Select extends FieldUI
{
    use WithDatasource;
    public function lazyLoad()
    {
        return $this->attr('wire:tom-select.lazyLoad', true, 'tom-select')->vars('lazyLoad', true);
    }
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
        $this->beforeRender(function ($base) {
            $base->attr('wire:key', 'tom-select-' . $base->getFieldNameWithoutPrefix() . '-' . time(), 'tom-select');
            $base->attr('wire:tom-select', null, 'tom-select');
            $base->attr('wire:tom-select.datasource', json_encode($base->getDatasource()), 'tom-select');
            if ($base->options) {
                $base->attr('wire:tom-select.options', json_encode($base->options), 'tom-select');
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
        return $this->register(function ($base) use ($action, $name) {
            if (!$name) {
                $name = 'select_remote_action';
            }
            $base->action($name, $action, true);
            $base->beforeRender(function ($base) use ($name) {
                $base->attr('wire:tom-select.remote-action', $base->getUIIDKey() .  $name, 'tom-select');
                if (
                    !$base->checkDataSource() &&
                    !$base->checkVar('lazyLoad', null) 
                ) {
                    $base->dataSource($base->getManager()?->callActionUI($base->getUIIDKey() . $name, ''));
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
        $attrTomSelect = $this->getAttr('tom-select');
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
        <div  {$attrTomSelect} >
            <div wire:ignore>
                <select {$attr} >
                    {$optionHtml}
                </select>
            </div>
        </div>
        HTML;
    }
}
