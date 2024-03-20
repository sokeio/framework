<?php

namespace Sokeio\Components\Field\Concerns;

use Sokeio\Components\UI;

trait withFieldOperator
{
    public static function getOperatorType($operator)
    {
        $operatorMap = [
            //Matches values that are equal to a specified value.
            '$eq' => '=',
            //Matches values that are greater than a specified value.
            '$gt' => '>',
            //Matches values that are greater than or equal to a specified value.
            '$gte' => '>=',
            //Matches any of the values specified in an array.
            '$in' => 'in',
            //Matches values that are less than a specified value.
            '$lt' => '<',
            //Matches values that are less than or equal to a specified value.
            '$lte' => '<=',
            //Matches all values that are not equal to a specified value.
            '$ne' => '<>',
            //Matches none of the values specified in an array.
            '$nin' => 'not in',
        ];
        return $operatorMap[$operator] ?? null;
    }
    public static function getOperatorOther($operator)
    {
        $operatorMap = [
            '$like' => [
                'keyOperator' => 'like',
                'formatValue' => '%' . static::FORMAT_VALUE . '%'
            ],
            '$nlike' => [
                'keyOperator' => 'not like',
                'formatValue' => '%' . static::FORMAT_VALUE . '%'
            ],
            '$withStart' => [
                'keyOperator' => 'like',
                'formatValue' => static::FORMAT_VALUE . '%'
            ],
            '$withEnd' => [
                'keyOperator' => 'like',
                'formatValue' => '%' . static::FORMAT_VALUE
            ],
        ];
        return $operatorMap[$operator] ?? null;
    }
    private const FORMAT_VALUE = '{{VALUE}}';
    /**
     * Apply search conditions to the query
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $search
     * @return \Illuminate\Database\Query\Builder
     */
    public static function operatorQuery($query, $search)
    {
        foreach ($search as $key => $value) {
            $keyOperator = self::getOperatorType($key);
            $formatValue = static::FORMAT_VALUE;
            $items = $value;
            if (!$keyOperator && $operationOther = self::getOperatorOther($key)) {
                $keyOperator = $operationOther['keyOperator'];
                $formatValue = $operationOther['formatValue'];
            }
            if (!$keyOperator) {
                $keyOperator = '=';
                $items = [$key => $value];
            }
            foreach ($items  as $fieldName => $fieldValue) {
                if (!$fieldValue) {
                    continue;
                }
                $arrFields = explode('.', self::getFieldName($fieldName));
                $formatFieldValue = str_replace(static::FORMAT_VALUE, $fieldValue, $formatValue);
                if (count($arrFields) == 1) {
                    $query->where(self::getFieldName($fieldName), $keyOperator, $formatFieldValue);
                } else {
                    $query->whereHas($arrFields[0], function ($query) use (
                        $keyOperator,
                        $arrFields,
                        $formatFieldValue
                    ) {
                        $query->where($arrFields[0] . '.' . $arrFields[1], $keyOperator, $formatFieldValue);
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
    public function eq()
    {
        $this->operatorType = '$eq';
        return $this;
    }
    public function gt()
    {
        $this->operatorType = '$gt';
        return $this;
    }
    public function gte()
    {
        $this->operatorType = '$gte';
        return $this;
    }
    public function in()
    {
        $this->operatorType = '$in';
        return $this;
    }
    public function lt()
    {
        $this->operatorType = '$lt';
        return $this;
    }
    public function lte()
    {
        $this->operatorType = '$lte';
        return $this;
    }
    public function ne()
    {
        $this->operatorType = '$ne';
        return $this;
    }
    public function nin()
    {
        $this->operatorType = '$nin';
        return $this;
    }
    public function like()
    {
        $this->operatorType = '$like';
        return $this;
    }
    public function nlike()
    {
        $this->operatorType = '$nlike';
        return $this;
    }
    public function withStart()
    {
        $this->operatorType = '$withStart';
        return $this;
    }
    public function withEnd()
    {
        $this->operatorType = '$withEnd';
        return $this;
    }
}
