<?php

namespace Sokeio\Components\Field\Concerns;

use Sokeio\Components\UI;

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
            $formatValue = '{{VALUE}}';
            $items = $value;
            if (!$keyOperator) {
                if ($key == '$like') {
                    $keyOperator = 'like';
                    $formatValue = '%{{VALUE}}%';
                } else if ($key == '$nlike') {
                    $keyOperator = 'not like';
                    $formatValue = '%{{VALUE}}%';
                } else if ($key == '$withStart') {
                    $keyOperator = 'like';
                    $formatValue = '{{VALUE}}%';
                } else if ($key == '$withEnd') {
                    $keyOperator = 'like';
                    $formatValue = '%{{VALUE}}';
                } else {
                    $keyOperator = '=';
                    $items = [$key => $value];
                }
            }
            foreach ($items  as $fieldName => $fieldValue) {
                if (!$fieldValue) continue;
                $arrFields = explode('.', self::getFieldName($fieldName));
                if (count($arrFields) == 1) {
                    $query->where(self::getFieldName($fieldName), $keyOperator, str_replace('{{VALUE}}', $formatValue, $fieldValue));
                } else {
                    $query->whereHas($arrFields[0], function ($query) use ($keyOperator, $formatValue, $arrFields, $fieldValue) {
                        $query->where($arrFields[0] . '.' . $arrFields[1], $keyOperator, str_replace('{{VALUE}}', $formatValue, $fieldValue));
                    });
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
