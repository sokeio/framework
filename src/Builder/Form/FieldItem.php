<?php

namespace BytePlatform\Builder\Form;

use BytePlatform\Builder\ItemBuilder;

class FieldItem extends ItemBuilder
{

    private $formField;
    public function setForm($form)
    {
        $this->formField = $form;
    }
    public function getForm()
    {
        return $this->formField;
    }


    private $viewField = 'byte::builder.field.text';
    public function setView($viewField)
    {
        $this->viewField = $viewField;
    }
    public function getView()
    {
        return $this->viewField;
    }
    public function Text()
    {
        $this->setView('byte::builder.field.text');
        return $this;
    }
    public function Password()
    {
        $this->setView('byte::builder.field.password');
        return $this;
    }
    public function Checkbox()
    {
        $this->setView('byte::builder.field.checkbox');
        return $this;
    }
    public function Number()
    {
        $this->setView('byte::builder.field.number');
        return $this;
    }
    public function Date()
    {
        $this->setView('byte::builder.field.date');
        return $this;
    }
    public function Time()
    {
        $this->setView('byte::builder.field.time');
        return $this;
    }
    public function DateTime()
    {
        $this->setView('byte::builder.field.date-time');
        return $this;
    }
    public function Radio()
    {
        $this->setView('byte::builder.field.radio');
        return $this;
    }
    public function Toggle()
    {
        $this->setView('byte::builder.field.toggle');
        return $this;
    }
    public function Textarea()
    {
        $this->setView('byte::builder.field.textarea');
        return $this;
    }
    public function Select()
    {
        $this->setView('byte::builder.field.select');
        return $this;
    }
    public function SelectMultiple()
    {
        $this->setView('byte::builder.field.select-multiple');
        return $this;
    }
}
