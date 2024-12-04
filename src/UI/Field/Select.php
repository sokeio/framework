<?php

namespace Sokeio\UI\Field;


class Select extends FieldUI
{
    protected $datasource;
    public function dataSource($datasource)
    {
        $this->datasource = $datasource;
        return $this;
    }
    private $options = [];
    public function options($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    public function optionsWithEnum($enum)
    {
        return $this->dataSource(collect($enum::cases())
            ->map(fn($item) => ['value' => $item->value, 'text' => $item->label($item->value)])
            ->values()->toArray());
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
            if ($this->datasource && is_callable($this->datasource)) {
                $this->datasource = call_user_func($this->datasource, $this);
            }
            $this->attr('wire:tom-select.datasource', json_encode($this->datasource ?? []));
            if ($this->options) {
                $this->attr('wire:tom-select.options', json_encode($this->options));
            }
        });
    }
    public function remoteActionWithModel(
        $model,
        $fieldSearch = ['name'],
        $fillable = ['id', 'name'],
        $fieldId = 'id',
        $filedText = 'name',
        $mapData = null,
        $limit = 30,
        $name = null
    ) {
        if (!$mapData || !is_callable($mapData)) {
            $mapData = fn($item) => ['value' => $item->{$fieldId}, 'text' => $item->{$filedText}, 'item' => $item];
        }
        return $this->remoteAction(function ($value) use ($model, $fieldSearch, $mapData, $fillable, $limit) {
            $fieldValue = $this->getValue();
            if (!$fieldValue || ! is_array($fieldValue)) {
                $fieldValue = [];
            }
            return ($model)::query()
                ->whereIn('id',  $fieldValue)
                ->union(
                    $model::query()
                        ->when($value, fn($query) => $query->whereAny($fieldSearch, 'like', '%' . $value . '%'))
                        ->whereNotIn('id', $fieldValue)
                        ->limit($limit - count($fieldValue))->select($fillable)
                )
                ->limit($limit)
                ->select($fillable)
                ->get()->map($mapData);
        }, $name);
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
