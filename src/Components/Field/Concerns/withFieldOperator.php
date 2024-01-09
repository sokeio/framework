<?php

namespace Sokeio\Admin\Components\Field\Concerns;

trait withFieldOperator
{
    public static function  getOperatorType($operator)
    {
        switch ($operator) {
                //Matches values that are equal to a specified value.
            case '$eq':
                return '=';
                //Matches values that are greater than a specified value.
            case '$gt':
                return '>';
                //Matches values that are greater than or equal to a specified value.
            case '$gte':
                return '>=';
                //Matches any of the values specified in an array.
            case '$in':
                return 'in';
                //Matches values that are less than a specified value.
            case '$lt':
                return '<';
                //Matches values that are less than or equal to a specified value.
            case '$lte':
                return '<=';
                //Matches all values that are not equal to a specified value.
            case '$ne':
                return '<>';
                //Matches none of the values specified in an array.
            case '$nin':
                return 'not in';
                // case '$like':
                //     return 'like';
                // case '$withStart':
                //     return 'withStart';
                // case '$withEnd':
                //     return 'withEnd';
        }
        return null;
    }
    public static function OperatorQuery($query, $search)
    {
        foreach ($search as $key => $value) {
            $keyOperator = self::getOperatorType($key);
            if ($keyOperator) {
                foreach ($value as $fieldName => $fieldValue) {
                    $query->where(self::getFieldName($fieldName), $keyOperator, $fieldValue);
                }
            } else if ($key == '$like') {
                foreach ($value as $fieldName => $fieldValue) {
                    $query->where(self::getFieldName($fieldName), 'like', '%' . $fieldValue . '%');
                }
            } else if ($key == '$nlike') {
                foreach ($value as $fieldName => $fieldValue) {
                    $query->where(self::getFieldName($fieldName), 'not like', '%' . $fieldValue . '%');
                }
            } else if ($key == '$withStart') {
                foreach ($value as $fieldName => $fieldValue) {
                    $query->where(self::getFieldName($fieldName), 'like', $fieldValue . '%');
                }
            } else if ($key == '$withEnd') {
                foreach ($value as $fieldName => $fieldValue) {
                    $query->where(self::getFieldName($fieldName), 'like',  '%' . $fieldValue);
                }
            }
        }
        return $query;
    }
    private $operatorType = '';
    public function getOperatorField()
    {
        return $this->operatorType;
    }
    public function EQ()
    {
        $this->operatorType = '$eq';
        return $this;
    }
    public function GT()
    {
        $this->operatorType = '$gt';
        return $this;
    }
    public function GTE()
    {
        $this->operatorType = '$gte';
        return $this;
    }
    public function In()
    {
        $this->operatorType = '$in';
        return $this;
    }
    public function LT()
    {
        $this->operatorType = '$lt';
        return $this;
    }
    public function LTE()
    {
        $this->operatorType = '$lte';
        return $this;
    }
    public function NE()
    {
        $this->operatorType = '$ne';
        return $this;
    }
    public function NIN()
    {
        $this->operatorType = '$nin';
        return $this;
    }
    public function LIKE()
    {
        $this->operatorType = '$like';
        return $this;
    }
    public function NLIKE()
    {
        $this->operatorType = '$nlike';
        return $this;
    }
    public function WithStart()
    {
        $this->operatorType = '$withStart';
        return $this;
    }
    public function WithEnd()
    {
        $this->operatorType = '$withEnd';
        return $this;
    }
}
