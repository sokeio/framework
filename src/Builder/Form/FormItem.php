<?php

namespace BytePlatform\Builder\Form;

use BytePlatform\Builder\ItemBuilder;

class FormItem extends ItemBuilder
{
    private $fields = [];
    public function Fields($arrs)
    {
        $this->fields = $arrs;
    }
    public function beforeRender()
    {
        parent::beforeRender();
        $temps = [];
        foreach ($this->fields as $item) {
            if ($item) {
                if (is_callable($item)) {
                    $temp = new FieldItem();
                    $temp->setForm($this);
                    $item($temp);
                    $temps[] = $temp;
                } else {
                    $item->setForm($this);
                    $temps[] = $item;
                }
            }
        }
        $this->fields = $temps;
        foreach ($this->fields as $item) {
            $item->beforeRender();
        }
    }
}
