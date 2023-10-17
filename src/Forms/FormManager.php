<?php

namespace BytePlatform\Forms;

use BytePlatform\BaseManager;
use BytePlatform\Item;
use BytePlatform\ItemForm;
use BytePlatform\FieldView;

class FormManager
{
    private const FIELD_DEFAULT = "text";
    /*
    * @var \BytePlatform\Forms\FieldView[] $fields
    */
    private static $fields = [];
    public static function RegisterField($field)
    {
        if (!$field) return;
        if (is_array($field)) {
            foreach ($field as $item) {
                self::RegisterField($item);
            }
            return;
        }
        self::$fields[$field->getFieldType()] = $field;
        FieldView::macro($field->getFieldType(), function () use ($field) {
            return  $field->getFieldType();
        });
    }
    private static function GetFieldView($type, $default = self::FIELD_DEFAULT)
    {
        if (isset(self::$fields[$type])) return self::$fields[$type];
        if (isset(self::$fields[$default])) return self::$fields[$default];
        if (isset(self::$fields[self::FIELD_DEFAULT])) return self::$fields[self::FIELD_DEFAULT];
    }
    public static function FieldRender(Item $item,  $itemForm = null, $dataId = null)
    {
        if ($item->getEdit()) {
            return view(self::GetFieldView($item->getType())->beforeRender($item)->getView(), [
                'item' => $item,
                'form' => $itemForm,
                'dataId' => $dataId
            ])->render();
        } else {
            return view(self::GetFieldView("readonly")->beforeRender($item)->getView(), [
                'item' => $item,
                'form' => $itemForm,
                'dataId' => $dataId
            ])->render();
        }
    }
    public static function FormRender(BaseManager $itemManager, ItemForm $itemForm = null, $dataId = null)
    {
        return view('byte::forms.render', [
            'manager' => $itemManager,
            'form' => $itemForm,
            'dataId' => $dataId
        ])->render();
    }
}
