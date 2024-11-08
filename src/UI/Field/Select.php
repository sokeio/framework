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
        return $this->options(collect($enum::cases())
            ->map(fn($item) => ['value' => $item->value, 'text' => $item->label($item->value)])
            ->values()->toArray());
    }
    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Create new item on select input if not exists.
     *
     * @return $this
     */
    /******  d9825501-2eaa-45b8-ac7f-47884bc5ba3f  *******/    public function createItem()
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
            if ($this->datasource) {
                $this->options = [];
                if (is_callable($this->datasource)) {
                    $this->datasource = call_user_func($this->datasource, $this);
                }
                if ($this->datasource) {
                    $this->options = $this->datasource;
                }
            }
            if ($this->options) {
                $this->attr('wire:tom-select.options', json_encode($this->options));
            }
        });
    }
    public function remoteActionWithModel(
        $model,
        $fieldSearch = ['name'],
        $fillable = ['id', 'name'],
        $mapData = null,
        $limit = 20,
        $name = null
    ) {
        if (!$mapData || !is_callable($mapData)) {
            $mapData = fn($item) => ['value' => $item->id, 'text' => $item->name, 'item' => $item];
        }
        return $this->remoteAction(function ($value) use ($model, $fieldSearch, $mapData, $fillable, $limit) {
            return ($model)::query()
                ->when($value, fn($query) => $query->whereAny($fieldSearch, 'like', '%' . $value . '%'))
                ->limit($limit)
                ->get($fillable)->map($mapData);
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
            $this->attr('wire:ignore', null, 'wrapper');
            $this->render(function () use ($name) {
                if (empty($this->dataSource)) {
                    $this->dataSource($this->getManager()->callActionUI($name));
                }
            });
        });
    }
    public function multiple()
    {
        return $this->attr('multiple');
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        $value = $this->getValue();
        return <<<HTML
        <select {$attr} >
            <option value="{$value}"></option>
        </select>
        HTML;
    }
}
