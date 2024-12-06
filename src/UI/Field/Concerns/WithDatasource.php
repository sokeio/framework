<?php

namespace Sokeio\UI\Field\Concerns;

trait WithDatasource
{
    protected $datasource;
    public function dataSource($datasource)
    {
        $this->datasource = $datasource;
        return $this;
    }
    public function getDatasource()
    {
        if ($this->datasource && is_callable($this->datasource)) {
            return call_user_func($this->datasource, $this) ?? [];
        }
        return $this->datasource ?? [];
    }
    public function checkDataSource()
    {
        if ($this->datasource && is_array($this->datasource)) {
            return count($this->datasource) > 0;
        }
        if ($this->datasource && is_callable($this->datasource)) {
            return true;
        }
        return false;
    }
    public function dataSourceWithEnum($enum)
    {
        return $this->dataSource(collect($enum::cases())
            ->map(fn($item) => ['value' => $item->value, 'text' => $item->label($item->value)])
            ->values()->toArray());
    }
    public function dataSourceWithModel(
        $model,
        $filedText = 'name',
        $fillable = null,
        $fieldId = 'id',
        $mapData = null,
        $limit = 30,
    ) {
        return $this->dataSource(function () use ($model, $filedText, $fillable, $fieldId, $mapData, $limit) {
            return $this->getDataByModel($model, $filedText, $fillable, $fieldId, $mapData, $limit);
        });
    }
    protected function getDataByModel(
        $model,
        $filedText = 'name',
        $fillable = null,
        $fieldId = 'id',
        $mapData = null,
        $limit = 30,
        $fieldSearch = null,
        $value = null
    ) {
        if (!$fillable) {
            $fillable = [$fieldId, $filedText];
        }
        if (!$fieldSearch) {
            $fieldSearch = [$fieldId];
        }
        if (!$mapData || !is_callable($mapData)) {
            $mapData = fn($item) => ['value' => $item->{$fieldId}, 'text' => $item->{$filedText}, 'item' => $item];
        }
        $fieldValue = $this->getValue();
        if($fieldValue===null||trim($fieldValue)===''){
            $fieldValue = [];
        }
        if ($fieldValue && ! is_array($fieldValue)) {
            $fieldValue = [$fieldValue];
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
    }
}
