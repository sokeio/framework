<?php

namespace Sokeio\UI\Support;

use Illuminate\Support\Facades\Log;
use Sokeio\UI\BaseUI;

class DataUI
{
    private function __construct(protected BaseUI $ui) {}
    private $data = [];
    public function set($key, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $this->data[$key] = $value;
        return $this->ui;
    }

    public function remove($key, $value = null)
    {
        $temp = $this->data;
        if ($value == null) {
            if (isset($temp[$key])) {
                unset($temp[$key]);
            }
        } else {
            $value = is_array($value) ? $value : [$value];
            $temp[$key] = array_diff($temp[$key], $value);
            if (count($temp[$key]) == 0) {
                unset($temp[$key]);
            }
        }
        $this->data = $temp;
        Log::info($this->data);
        return $this->ui;
    }
    public function append($key, $value)
    {
        $value = is_array($value) ? $value : [$value];
        if (!isset($this->data[$key])) {
            $this->data[$key] = [];
        }
        $this->data[$key] = array_merge($this->data[$key], $value);
        return $this->ui;
    }
    public function get($key, $default = null, $isText = false, $separator = ' ')
    {
        if (isset($this->data[$key]) && $isText) {
            return trim(implode($separator, $this->data[$key]));
        }
        return $this->data[$key] ?? $default;
    }
    public function getData()
    {
        return $this->data ?: [];
    }
    public function getAttributeText($checkFn = null)
    {
        $attr = '';
        foreach ($this->getData() as $key => $value) {

            if ($checkFn && is_callable($checkFn)) {
                $attr .= call_user_func($checkFn, $key, $value);
            } else {
                if (is_array($value)) {
                    // foreach ($value as $k => $v) {
                    //     if (is_callable($v)) {
                    //         dd(['key' => $key, 'value' => $v]);
                    //     }
                    // }
                    $value = implode(' ', $value);
                }
                $attr .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }
        return $attr;
    }
    public function group($key)
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = new DataUI($this->ui);
        }
        return $this->data[$key];
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            if ($value instanceof DataUI) {
                $data['_' . $key] = $value->toArray();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public static function parse($data, $ui = null)
    {
        $dataUI = new DataUI($ui);
        foreach ($data as $key => $value) {
            // check prefix _
            if (substr($key, 0, 1) === '_') {
                $key = substr($key, 1);
                foreach ($value as $k => $v) {
                    $dataUI->group($key)->set($k, $v);
                }
            }
        }
        return $dataUI;
    }
    public static function create($ui = null)
    {
        return new DataUI($ui);
    }
}
