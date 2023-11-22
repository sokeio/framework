<?php

namespace Sokeio;


class ItemForms extends DataForm
{
    public static function FormId($id)
    {
        return $id;
    }
    public function addValidationRulesToComponent()
    {
        $this->___enableBindData = false;
        parent::addValidationRulesToComponent();
    }
    public function BindData($arr)
    {
        foreach ($arr as $item) {
            $proForm = self::FormId($item->id);
            if (!isset($this->___templateData[$proForm])) {
                $dataForm = new ItemForm($this->component, $this->getPropertyName() . '.' . $proForm);
                $this->itemManager->Data($item);
                $dataForm->setDataId($item->id)->DataToForm($item);
                $this->___templateData[$proForm] =  $dataForm;
            }
        }
    }
    public function DataFromFormById($id)
    {
        $form = $this->{self::FormId($id)};
        if (isset($form) && $form) {
            return $form->setDataId($id)->DataFromForm(true);
        }
    }
}
