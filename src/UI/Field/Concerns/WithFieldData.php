<?php

namespace Sokeio\UI\Field\Concerns;

trait WithFieldData
{

    private $arrQuery = [];
    public function withQuery($match = null, $key = null, $fnValue = null)
    {
        if (!$match && !is_callable($key)) {
            $this->arrQuery[] = function ($query, $value) use ($key, $match, $fnValue) {
                if ($key == null) {
                    $key = $this->getFieldNameWithoutPrefix();
                }
                if ($fnValue && is_callable($fnValue)) {
                    $value = $fnValue($value);
                }
                if ($value === ''||$value === null) {
                    return $query;
                }
                if ($match) {
                    if ($match == 'like' && !str($value)->contains('%')) {
                        $value = '%' . $value . '%';
                    }
                    $query->where($key, $match, $value);
                } else {
                    $query->where($key, $value);
                }
                return $query;
            };
        } elseif (is_callable($match)) {
            $this->arrQuery[] = $match;
        }
        return $this;
    }
    public function applyQuery($query)
    {
        $value = $this->getValue();
        foreach ($this->arrQuery as $q) {
            $q($query, $value);
        }
        return $query;
    }
    private $fillCallback = null;
    private $skipFill = false;
    public function skipFill()
    {
        $this->skipFill = true;
        return $this;
    }
    public function fill($callback)
    {
        $this->fillCallback = $callback;
    }
    public function fillToModel($model)
    {
        if ($this->skipFill) {
            return;
        }
        if ($this->fillCallback && is_callable($this->fillCallback)) {
            call_user_func($this->fillCallback, $model, $this);
        } else {
            data_set($model, $this->getFieldNameWithoutPrefix(), $this->getValue());
        }
    }
}
