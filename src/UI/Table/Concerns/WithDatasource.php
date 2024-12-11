<?php

namespace Sokeio\UI\Table\Concerns;

trait WithDatasource
{
    protected $datasource;
    private $arrQuery = [];
    public function dataSource($datasource)
    {
        $this->datasource = $datasource;
        return $this;
    }
    public function getDatasource()
    {
        if ($this->datasource && !is_string($this->datasource) && is_callable($this->datasource)) {
            return call_user_func($this->datasource, $this) ?? [];
        }
        if ($this->checkVar('showAll', true)) {
            $this->datasource = $this->applyQuery()?->get();
        } else {
            $pageSize = $this->getValueByName('page.size', 15);
            $pageIndex = $this->getValueByName('page.index', 1);
            $this->datasource = $this->applyQuery()?->paginate($pageSize, ['*'], 'p' . $this->getUIIDkey(), $pageIndex);
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
    public function withQuery($key, $value = null, $match = null)
    {
        if (!$key) {
            return $this;
        }
        if (is_string($key)) {
            $this->arrQuery[] = function ($query) use ($key, $match, $value) {
                if ($match) {
                    $query->where($key, $match, $value);
                } else {

                    $query->where($key, $value);
                }
                return $query;
            };
        } elseif (is_callable($key)) {
            $this->arrQuery[] = $key;
        }
        return $this;
    }
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }
    public function applyQuery()
    {
        $query = $this->query;
        $orderBy = $this->getValueByName('order.field');
        $type = $this->getValueByName('order.type', 'asc');
        if ($orderBy) {
            $query = $query->orderBy($orderBy, $type);
        }
        $fields = $this->getManager()?->getFieldsByGroup(['formSearch', 'formSearchExtra']);
        foreach ($fields as $field) {
            $field->applyQuery($query);
        }
        foreach ($this->arrQuery as $q) {
            $q($query);
        }
        return $query;
    }
}
